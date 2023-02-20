<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Monolog;

use Frontastic\Catwalk\ApiCoreBundle\Domain\CustomerService;
use Monolog\Formatter\FormatterInterface;

class JsonFormatter implements FormatterInterface
{
    const LOG_SOURCE = 'catwalk-php';

    /**
     * @var CustomerService
     */
    private $customerService;

    private $applicationEnvironment;

    public function __construct(CustomerService $customerService, string $applicationEnvironment)
    {
        $this->customerService = $customerService;
        $this->applicationEnvironment = $applicationEnvironment;
    }

    public function format(array $record)
    {
        $logData = $record['extra'];

        if (isset($record['context']) && isset($record['context']['exception'])) {
            $exception = $record['context']['exception'];
            $logData['exception'] = $this->formatException($exception);
            unset($record['context']['exception']);
        }

        $logData = array_merge(
            $logData,
            $this->formatGenericLogInfo($record)
        );

        return json_encode((object) $logData) . "\n";
    }

    public function formatBatch(array $records)
    {
        $message = '';
        foreach ($records as $record) {
            $message .= $this->format($record);
        }

        return $message;
    }

    /**
     * Trying to get the project id. If we encounter an error (most likely we are not in a project folder then), we will
     * return 'catwalk' as projectId here.
     *
     * @return string
     */
    private function getProjectId(): string
    {
        try {
            // In catwalk there is always just 1 project, the current one
            return $this->customerService->getCustomer()->projects[0]->projectId;
        } catch (\Throwable $e) {
            return 'catwalk';
        }
    }

    private function formatException($exception): array
    {
        $data = [];

        if (is_object($exception)) {
            $data['class'] = get_class($exception);
        } elseif (is_string($exception)) {
            $data['message'] = $exception;
        }

        if (!$exception instanceof \Throwable) {
            return $data;
        }
        $data['message'] = $exception->getMessage();
        $data['file'] = $exception->getFile();
        $data['code'] = $exception->getCode();
        $data['trace'] = $this->formatExceptionTrace($exception);
        if ($exception->getPrevious() instanceof \Throwable) {
            $data['previous'] = $this->formatException($exception->getPrevious());
        }

        return $data;
    }

    private function formatGenericLogInfo(array $record): array
    {
        // Format see https://www.notion.so/frontastic/JSON-Logging-Format-7aa12f53846041f08f4d1526b64bd335
        return [
            'logSource' => $record['logSource'] ?? self::LOG_SOURCE,
            'project' => $this->getProjectId(),

            '@timestamp' => (isset($record['datetime']) && ($record['datetime'] instanceof \DateTimeInterface)
                ? ($record['datetime']->format('c'))
                : (new \DateTimeImmutable('now'))->format('c')),
            'message' => $record['message'],
            'severity' => strtoupper($record['level_name']),
            'context' => $record['context'],
            'channel' => $record['channel'],
            'level' => $record['level'],
        ];
    }

    private function formatExceptionTrace(\Throwable $exception): array
    {
        $environment = $this->applicationEnvironment;

        $stackTrace =  array_map(function ($traceElement) {
            unset($traceElement['args']);
            return $traceElement;
        }, $exception->getTrace())[0];

        if (in_array($environment, ['prod', 'production'])) {
          return  array_slice($stackTrace, 0, 2);
        }

        return $stackTrace;
    }
}

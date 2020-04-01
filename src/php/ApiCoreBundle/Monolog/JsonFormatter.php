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

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    public function format(array $record)
    {
        // Format see https://www.notion.so/frontastic/JSON-Logging-Format-7aa12f53846041f08f4d1526b64bd335
        $logData = array_merge(
            $record['extra'],
            [
                'logSource' => self::LOG_SOURCE,
                'project' => $this->getProjectId(),

                '@timestamp' => (isset($record['datetime']) && ($record['datetime'] instanceof \DateTimeInterface)
                    ? ($record['datetime']->format('c'))
                    : (new \DateTimeImmutable('now'))->format('c')),
                'message' => $record['message'],
                'severity' => strtoupper($record['level_name']),

                'channel' => $record['channel'],
                'level' => $record['level'],
            ]
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
}

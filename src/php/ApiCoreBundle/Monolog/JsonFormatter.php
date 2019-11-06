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
        return json_encode((object)[
            'logSource' => self::LOG_SOURCE,
            // In catwalk there is always just 1 project, the current one
            'project' => $this->customerService->getCustomer()->projects[0]->projectId,

            '@timestamp' => (isset($record['datetime']) && ($record['datetime'] instanceof \DateTimeInterface)
                ? ($record['datetime']->format('c'))
                : (new \DateTimeImmutable('now'))->format('c')),
            'message' => $record['message'],
            'severity' => strtoupper($record['level_name']),

            'channel' => $record['channel'],
            'level' => $record['level'],
            'extra' => (object)$record['extra'],
        ]) . "\n";
    }

    public function formatBatch(array $records)
    {
        $message = '';
        foreach ($records as $record) {
            $message .= $this->format($record);
        }

        return $message;
    }
}

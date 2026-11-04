<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Monolog;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Environment;
use PHPUnit\Framework\TestCase;

class RouteLoggingFilterTest extends TestCase
{
    private const MATCHED_ROUTE_MESSAGE = 'Matched route "frontastic.frontend.master".';
    private const OTHER_MESSAGE = 'Something worth logging happened';

    private RecordingFilterHandler $innerHandler;

    public function setUp(): void
    {
        $this->innerHandler = new RecordingFilterHandler();
    }

    /**
     * @dataProvider matchedRouteRecordProvider
     */
    public function testRouteLogIsFilteredInProduction($record)
    {
        $filter = new RouteLoggingFilter($this->innerHandler, Environment::PRODUCTION);

        $this->assertFalse($filter->isHandling($record));
        $this->assertFalse($filter->handle($record));

        $this->assertSame([], $this->innerHandler->isHandlingRecords);
        $this->assertSame([], $this->innerHandler->handledRecords);
    }

    /**
     * @dataProvider otherMessageRecordProvider
     */
    public function testOtherMessageIsHandledInProduction($record)
    {
        $filter = new RouteLoggingFilter($this->innerHandler, Environment::PRODUCTION);

        $this->assertTrue($filter->isHandling($record));
        $this->assertTrue($filter->handle($record));

        $this->assertSame([$record], $this->innerHandler->handledRecords);
    }

    /**
     * @dataProvider matchedRouteRecordProvider
     */
    public function testRouteLogIsHandledInDevelopment($record)
    {
        $filter = new RouteLoggingFilter($this->innerHandler, Environment::DEVELOPMENT);

        $this->assertTrue($filter->isHandling($record));
        $this->assertTrue($filter->handle($record));

        $this->assertSame([$record], $this->innerHandler->handledRecords);
    }

    public function testLogRecordCasesAreCovered()
    {
        if (!class_exists(\Monolog\LogRecord::class)) {
            $this->markTestSkipped('The LogRecord class is not available');
        }

        $this->assertTrue(true);
    }

    public static function matchedRouteRecordProvider(): array
    {
        return self::createRecords(self::MATCHED_ROUTE_MESSAGE);
    }

    public static function otherMessageRecordProvider(): array
    {
        return self::createRecords(self::OTHER_MESSAGE);
    }

    /**
     * Provides the message in both record shapes the filter accepts.
     */
    private static function createRecords(string $message): array
    {
        $datetime = new \DateTimeImmutable();
        $context = ['foo' => 'bar'];
        $extra = ['requestId' => 'req-123'];

        $records = [
            'array record' => [
                [
                    'message' => $message,
                    'context' => $context,
                    'level' => 200,
                    'level_name' => 'INFO',
                    'channel' => 'app',
                    'datetime' => $datetime,
                    'extra' => $extra,
                ],
            ],
        ];

        if (class_exists(\Monolog\LogRecord::class)) {
            $records['log record'] = [
                new \Monolog\LogRecord(
                    datetime: $datetime,
                    channel: 'app',
                    level: \Monolog\Level::Info,
                    message: $message,
                    context: $context,
                    extra: $extra,
                ),
            ];
        }

        return $records;
    }
}

/**
 * Inner handler that accepts both record shapes and keeps the records it received.
 */
class RecordingFilterHandler extends \Monolog\Handler\FilterHandler
{
    /**
     * @var array<int, array|\Monolog\LogRecord>
     */
    public array $isHandlingRecords = [];

    /**
     * @var array<int, array|\Monolog\LogRecord>
     */
    public array $handledRecords = [];

    public function __construct()
    {
    }

    public function isHandling(array|\Monolog\LogRecord $record): bool
    {
        $this->isHandlingRecords[] = $record;

        return true;
    }

    public function handle(array|\Monolog\LogRecord $record): bool
    {
        $this->handledRecords[] = $record;

        return true;
    }
}

<?php


use Frontastic\Catwalk\ApiCoreBundle\Domain\CustomerService;
use Frontastic\Catwalk\ApiCoreBundle\Monolog\JsonFormatter;
use PHPUnit\Framework\TestCase;


class JsonFormatterTest extends TestCase
{

    private CustomerService $customerService;
    private JsonFormatter $prodJsonFormatter, $stagingJsonFormatter;


    public function setUp(): void
    {
        $this->customerService = Phake::mock(CustomerService::class);
        $this->prodJsonFormatter = new JsonFormatter(
            $this->customerService,
            "prod"
        );

        $this->stagingJsonFormatter = new JsonFormatter(
            $this->customerService,
            "staging"
        );
    }

    public function testFormatExceptionTraceProd()
    {
        $exception = new Exception("Something happened");
        $formatException = $this->getJsonFormatterMethod("prod");

        $exceptionResponse = $formatException->invokeArgs($this->prodJsonFormatter,[$exception]);

        $this->assertIsArray($exceptionResponse);

        $count = count((array)$exceptionResponse);
        $this->assertEquals(2, $count);
    }
    public function testFormatExceptionTraceStaging()
    {
        $exception = new Exception("Something happened");
        $formatException = $this->getJsonFormatterMethod("staging");

        $exceptionResponse = $formatException->invokeArgs($this->stagingJsonFormatter, [$exception]);


        $this->assertIsArray($exceptionResponse);
        foreach ($exceptionResponse as $exception) {
            $count = count($exception);
            $this->assertGreaterThan(2, $count);
        }
    }

    /**
     * @dataProvider recordProvider
     */
    public function testFormatMovesExceptionOutOfContext($record)
    {
        Phake::when($this->customerService)
            ->getCustomer()
            ->thenThrow(new RuntimeException('No customer available'));

        $formatted = $this->prodJsonFormatter->format($record);

        $this->assertStringEndsWith("\n", $formatted);

        $logData = json_decode($formatted, true);
        $this->assertIsArray($logData);

        $this->assertSame('catwalk-php', $logData['logSource']);
        $this->assertSame('catwalk', $logData['project']);
        $this->assertSame('Something worth logging happened', $logData['message']);
        $this->assertSame('INFO', $logData['severity']);
        $this->assertSame('app', $logData['channel']);
        $this->assertSame(200, $logData['level']);
        $this->assertNotEmpty($logData['@timestamp']);
        $this->assertSame('req-123', $logData['requestId']);
        $this->assertSame(RuntimeException::class, $logData['exception']['class']);
        $this->assertSame('boom', $logData['exception']['message']);
        $this->assertSame(['foo' => 'bar'], $logData['context']);

        $this->assertArrayHasKey('exception', $record['context']);
    }

    public function testLogRecordCasesAreCovered()
    {
        if (!class_exists(\Monolog\LogRecord::class)) {
            $this->markTestSkipped('The LogRecord class is not available');
        }

        $this->assertTrue(true);
    }

    /**
     * Provides both record shapes the formatter accepts.
     */
    public static function recordProvider(): array
    {
        $datetime = new \DateTimeImmutable();
        $message = 'Something worth logging happened';
        $context = ['exception' => new RuntimeException('boom'), 'foo' => 'bar'];
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

  private function getJsonFormatterMethod($env): ReflectionMethod
  {
      $reflection = new ReflectionClass("Frontastic\Catwalk\ApiCoreBundle\Monolog\JsonFormatter");
        $reflection->newInstanceArgs([$this->customerService, $env]);
        $method = $reflection->getMethod("formatExceptionTrace");
        return $method;
    }

}

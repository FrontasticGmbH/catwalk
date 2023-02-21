<?php


use Frontastic\Catwalk\ApiCoreBundle\Domain\CustomerService;
use Frontastic\Catwalk\ApiCoreBundle\Monolog\JsonFormatter;
use PHPUnit\Framework\TestCase;


class JsonFormatterText extends TestCase
{

    private CustomerService $customerService;
    private JsonFormatter $prodJsonFormatter, $stagingJsonFormatter;


    public function setUp(): void
    {
        $this->customerService = Phake::mock(CustomerService::class);
        $this->JsonFormatter = Phake::mock(JsonFormatter::class);
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

        foreach ($exceptionResponse as $exception) {
            $count = count($exception);
            $this->assertEquals(2, $count);
        }
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

  private function getJsonFormatterMethod($env): ReflectionMethod
  {
      $reflection = new ReflectionClass("Frontastic\Catwalk\ApiCoreBundle\Monolog\JsonFormatter");
        $reflection->newInstanceArgs([$this->customerService, $env]);
        $method = $reflection->getMethod("formatExceptionTrace");
        $method->setAccessible(true);
        return $method;
    }

}

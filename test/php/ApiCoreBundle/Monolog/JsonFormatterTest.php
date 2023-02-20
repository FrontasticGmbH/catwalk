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
        $formatException = $this->getAppMethod("formatExceptionTrace", "prod");

        $exceptionResponse = $formatException->invokeArgs($this->prodJsonFormatter,[$exception]);

        $this->assertIsArray($exceptionResponse);
        $this->assertArrayHasKey("file", $exceptionResponse);
        $this->assertArrayHasKey("line", $exceptionResponse);
    }
    public function testFormatExceptionTraceStaging()
    {
        $exception = new Exception("Something happened");
        $formatException = $this->getAppMethod("formatExceptionTrace", "staging");

        $exceptionResponse = $formatException->invokeArgs($this->stagingJsonFormatter, [$exception]);

        $this->assertIsArray($exceptionResponse);
        $this->assertArrayHasKey("file", $exceptionResponse);
        $this->assertArrayHasKey("line", $exceptionResponse);
        $this->assertArrayHasKey("function", $exceptionResponse);

    }

  public function getAppMethod($name, $env): ReflectionMethod
  {
        $reflection = new ReflectionClass("Frontastic\Catwalk\ApiCoreBundle\Monolog\JsonFormatter");
        $reflection->newInstanceArgs([$this->customerService, $env]);
        $method = $reflection->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }

}

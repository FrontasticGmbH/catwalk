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
        $formatException = self::getMethod("formatException");

        $exceptionResponse = $formatException->invokeArgs($this->prodJsonFormatter, [$exception]);

        $this->assertIsArray($exceptionResponse);
        $this->assertArrayHasKey("file", $exceptionResponse);
        $this->assertArrayHasKey("line", $exceptionResponse);
    }
//    public function testFormatExceptionTraceStaging()
//    {
//        $exception = new Exception("Something happened");
//        $formatException = self::getMethod("formatException");
//
//        $exceptionResponse = $formatException->invokeArgs($this->stagingJsonFormatter, [$exception]);
//
//
//        $this->assertIsArray($exceptionResponse);
//        $this->assertArrayHasKey("file", $exceptionResponse);
//        $this->assertArrayHasKey("line", $exceptionResponse);
//        $this->assertArrayHasKey("trace", $exceptionResponse);
//
//    }

    protected static function getMethod($name) {

        $JsonClass = new JsonFormatter();

        $class = new ReflectionClass("JsonFormatter.php");
        return $class->getMethod($name);
    }

}

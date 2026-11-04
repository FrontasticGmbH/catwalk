<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Monolog;

use Frontastic\Catwalk\FrontendBundle\EventListener\RequestIdListener;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class FrontasticLogProcessorTest extends TestCase
{
    private const REQUEST_ID = 'req-123';

    /**
     * @dataProvider recordProvider
     */
    public function testRequestInformationIsAddedToExtra($record)
    {
        $requestStack = new RequestStack();
        $requestStack->push($this->createRequest(self::REQUEST_ID));

        $processor = new FrontasticLogProcessor($requestStack);

        $result = $processor($record);

        if (is_object($record)) {
            $this->assertSame($record, $result);
        }

        $extra = $result['extra'];
        $this->assertSame(self::REQUEST_ID, $extra['requestId']);
        $this->assertSame(
            [
                'path' => '/some/path',
                'host' => 'example.test',
            ],
            $extra['request']
        );
        $this->assertSame('existingValue', $extra['existingKey']);
    }

    /**
     * @dataProvider recordProvider
     */
    public function testRecordIsUnchangedWithoutRequest($record)
    {
        $processor = new FrontasticLogProcessor(new RequestStack());

        $this->assertSame($record, $processor($record));
    }

    /**
     * @dataProvider recordProvider
     */
    public function testRecordIsUnchangedWithoutRequestIdAttribute($record)
    {
        $requestStack = new RequestStack();
        $requestStack->push($this->createRequest(null));

        $processor = new FrontasticLogProcessor($requestStack);

        $this->assertSame($record, $processor($record));
    }

    public function testLogRecordCasesAreCovered()
    {
        if (!class_exists(\Monolog\LogRecord::class)) {
            $this->markTestSkipped('The LogRecord class is not available');
        }

        $this->assertTrue(true);
    }

    /**
     * Provides both record shapes the processor accepts.
     */
    public static function recordProvider(): array
    {
        $datetime = new \DateTimeImmutable();
        $message = 'Something worth logging happened';
        $context = ['foo' => 'bar'];
        $extra = ['existingKey' => 'existingValue'];

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

    private function createRequest(?string $requestId): Request
    {
        $request = Request::create('/some/path', 'GET', [], [], [], ['HTTP_HOST' => 'example.test']);

        if ($requestId !== null) {
            $request->attributes->set(RequestIdListener::REQUEST_ID_ATTRIBUTE_KEY, $requestId);
        }

        return $request;
    }
}

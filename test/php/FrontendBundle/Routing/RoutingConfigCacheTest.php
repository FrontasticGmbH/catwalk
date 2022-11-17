<?php

namespace Frontastic\Catwalk\FrontendBundle\Routing;

use Frontastic\Catwalk\ApiCoreBundle\Domain\RandomValueProvider;
use Frontastic\Catwalk\ApiCoreBundle\Domain\TimeProvider;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class RoutingConfigCacheTest extends TestCase
{
    const NOW = 1000; // Awesome unix timestamp :D
    /**
     * @var LoggerInterface|\Phake\IMock
     */
    private $loggerMock;

    /**
     * @var TimeProvider|\Phake\IMock
     */
    private $timeProviderMock;

    /**
     * @var RandomValueProvider|\Phake\IMock
     */
    private $randomValueProvider;

    private \org\bovigo\vfs\vfsStreamDirectory $vfsRoot;

    public function setUp(): void
    {
        $this->loggerMock = \Phake::mock(LoggerInterface::class);
        $this->timeProviderMock = \Phake::mock(TimeProvider::class);
        $this->randomValueProvider = \Phake::mock(RandomValueProvider::class);

        $this->vfsRoot = vfsStream::setup('cache');

        \Phake::when($this->timeProviderMock)->microtimeNow->thenReturn((float) self::NOW);

        $cacheClass = new \ReflectionClass(RoutingConfigCache::class);
        $cacheClass->setStaticPropertyValue('regenerateInThisRequest', null);
    }

    public function testIsNotFreshFileNotExists()
    {
        $cache = new RoutingConfigCache(
            $this->vfsRoot->url() . '/not-exists-before',
            $this->loggerMock,
            $this->timeProviderMock,
            $this->randomValueProvider,
            false
        );

        $this->assertFalse($cache->isFresh());
    }

    public function testIsFresh()
    {
        $cacheFile = vfsStream::newFile('fresh-cache')->at($this->vfsRoot)->lastAttributeModified(
            self::NOW - 30, // Created 30 seconds earlier
        );

        // Maximum probability, because time base fresh should be fine
        \Phake::when($this->randomValueProvider)->randomInt->thenReturn(\PHP_INT_MAX);

        $cache = new RoutingConfigCache(
            $cacheFile->url(),
            $this->loggerMock,
            $this->timeProviderMock,
            $this->randomValueProvider,
            false
        );

        $this->assertTrue($cache->isFresh());
    }

    public function testIsNotFreshMaximumProbability()
    {
        $now = self::NOW;

        $cacheFile = vfsStream::newFile('fresh-cache')->at($this->vfsRoot)->lastAttributeModified(
            self::NOW - 61, // Created 61 seconds earlier
        );
        // Maximum probability will lead to cache being re-generated after 60 seconds
        \Phake::when($this->randomValueProvider)->randomInt->thenReturn(\PHP_INT_MAX);

        $cache = new RoutingConfigCache(
            $cacheFile->url(),
            $this->loggerMock,
            $this->timeProviderMock,
            $this->randomValueProvider,
            false
        );

        $this->assertFalse($cache->isFresh());
    }

    public function testIsNotFreshMinimumProbability()
    {
        $now = self::NOW;

        $cacheFile = vfsStream::newFile('non-fresh-cache')->at($this->vfsRoot)->lastAttributeModified(
            self::NOW - 120, // Created 120 seconds earlier
        );
        // Minimum probability lets cache still be re-generated after 120 seconds
        \Phake::when($this->randomValueProvider)->randomInt->thenReturn(0);

        $cache = new RoutingConfigCache(
            $cacheFile->url(),
            $this->loggerMock,
            $this->timeProviderMock,
            $this->randomValueProvider,
            false
        );

        $this->assertFalse($cache->isFresh());
    }

    public function testIsNotFreshMediumProbability()
    {
        $now = self::NOW;

        $cacheFile = vfsStream::newFile('semi-fresh-cache')->at($this->vfsRoot)->lastAttributeModified(
            self::NOW - 91, // Created 91 seconds earlier
        );
        // 50% probability with 50+% of overtime
        \Phake::when($this->randomValueProvider)->randomInt->thenReturn((int)round(\PHP_INT_MAX / 2));

        $cache = new RoutingConfigCache(
            $cacheFile->url(),
            $this->loggerMock,
            $this->timeProviderMock,
            $this->randomValueProvider,
            false
        );

        $this->assertFalse($cache->isFresh());
    }

    public function testIsNotFreshContinuationThroughRequest()
    {
        $now = self::NOW;

        $cacheFile = vfsStream::newFile('semi-fresh-cache')->at($this->vfsRoot)->lastAttributeModified(
            self::NOW - 91, // Created 91 seconds earlier
        );

        $cache = new RoutingConfigCache(
            $cacheFile->url(),
            $this->loggerMock,
            $this->timeProviderMock,
            $this->randomValueProvider,
            false
        );

        // Maximum probability in first run
        \Phake::when($this->randomValueProvider)->randomInt->thenReturn(\PHP_INT_MAX);

        $this->assertFalse($cache->isFresh());

        // Minimum probability in second run
        \Phake::when($this->randomValueProvider)->randomInt->thenReturn(0);

        $this->assertFalse($cache->isFresh());
    }
}

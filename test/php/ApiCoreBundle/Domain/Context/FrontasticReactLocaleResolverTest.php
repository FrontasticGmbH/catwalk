<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain\Context;

use Frontastic\Common\ReplicatorBundle\Domain\Project;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class FrontasticReactLocaleResolverTest extends TestCase
{
    /**
     * @var LocaleResolver
     */
    private $localeDeterminer;

    public function setUp(): void
    {
        $this->localeDeterminer = new FrontasticReactLocaleResolver();
    }

    public function testLocaleChosenFromSession()
    {
        $request = $this->createRequest();
        $project = $this->createProjectFixture();

        $request->getSession()->set('locale', 'de_DE');

        $this->assertSame(
            'de_DE',
            $this->localeDeterminer->determineLocale($request, $project)
        );
    }

    public function testLocaleInRequestOverwritesSession()
    {
        $request = $this->createRequest();
        $project = $this->createProjectFixture();

        $request->getSession()->set('locale', 'de_DE');
        $request->request->set('locale', 'de_CH');

        $this->assertSame(
            'de_CH',
            $this->localeDeterminer->determineLocale($request, $project)
        );
        $this->assertSame(
            'de_CH',
            $request->getSession()->get('locale')
        );
    }

    public function testLocaleGuessedFromBrowserPerfectMatch()
    {
        $request = $this->createRequest();
        $project = $this->createProjectFixture();

        $request->headers->set('Accept-Language', 'de-DE');

        $this->assertSame(
            'de_DE',
            $this->localeDeterminer->determineLocale($request, $project)
        );
    }

    public function testLocaleGuessedFromBrowserSimplified()
    {
        $request = $this->createRequest();
        $project = $this->createProjectFixture();

        $request->headers->set('Accept-Language', 'de');

        $this->assertSame(
            'de_DE',
            $this->localeDeterminer->determineLocale($request, $project)
        );
    }

    public function testLocaleGuessedFromBrowserComplexHeaderFormat()
    {
        $request = $this->createRequest();
        $project = $this->createProjectFixture();

        $request->headers->set('Accept-Language', 'fr-CH, fr;q=0.9, de;q=0.75, *;q=0.5');

        $this->assertSame(
            'de_DE',
            $this->localeDeterminer->determineLocale($request, $project)
        );
    }

    public function testLocaleGuessedFromBrowserComplexHeaderUnordered()
    {
        $request = $this->createRequest();
        $project = $this->createProjectFixture();

        $request->headers->set('Accept-Language', 'fr-CH, fr;q=0.9, en;q=0.4, de;q=0.75, *;q=0.5');

        $this->assertSame(
            'de_DE',
            $this->localeDeterminer->determineLocale($request, $project)
        );
    }

    private function createRequest(): Request
    {
        $request = new Request();
        $request->setSession(new Session(new MockArraySessionStorage()));

        return $request;
    }

    private function createProjectFixture(): Project
    {
        $project = new Project([
            'languages' => ['en_GB', 'en_US', 'de_DE', 'de_CH'],
            'defaultLanguage' => 'en_GB',
        ]);

        return $project;
    }
}

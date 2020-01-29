<?php

namespace Frontastic\Catwalk\FrontendBundle\Command;

use Frontastic\Catwalk\FrontendBundle\Domain\NodeService;
use Frontastic\Catwalk\FrontendBundle\Domain\PageService;
use Frontastic\Catwalk\IntegrationTest;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class GenerateSitemapsCommandTest extends IntegrationTest
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var array<string>
     */
    private $removeFilesOnTearDown = [];

    public function testEmptySitemapIsGenerated()
    {
        $commandTester = $this->givenAGenerateSitemapsCommandTester();
        $outputDirectory = $this->givenAnOutputDirectory();

        $commandTester->execute([
            'output-directory' => $outputDirectory,
            '--with-nodes' => true,
            '--with-nodes-subpages' => true,
            '--with-extensions' => true,
        ]);

        $this->assertTheOutputDirectoryMatchesTheFixture($outputDirectory, 'empty');
    }

    public function testGeneratesSitemapForSingleNode()
    {
        $commandTester = $this->givenAGenerateSitemapsCommandTester();
        $outputDirectory = $this->givenAnOutputDirectory();
        $this->givenTheNodeAndPageFixture('singleNode');

        $commandTester->execute([
            'output-directory' => $outputDirectory,
            '--with-nodes' => true,
            '--with-nodes-subpages' => true,
            '--with-extensions' => true,
        ]);

        $this->assertTheOutputDirectoryMatchesTheFixture($outputDirectory, 'singleNode');
    }

    public function setUp()
    {
        parent::setUp();

        $this->filesystem = new Filesystem();
    }

    protected function tearDown()
    {
        $this->filesystem->remove($this->removeFilesOnTearDown);
        $this->removeFilesOnTearDown = [];

        parent::tearDown();
    }

    private function givenAGenerateSitemapsCommandTester(): CommandTester
    {
        $command = new GenerateSitemapsCommand();
        $command->setContainer(self::getContainer());

        return new CommandTester($command);
    }

    private function givenAnOutputDirectory(): string
    {
        $tempDirectory = $this->givenATempDirectory();
        $outputDirectory = $tempDirectory . '/public/';
        $this->filesystem->mkdir($outputDirectory);
        return $outputDirectory;
    }

    private function givenATempDirectory(): string
    {
        $path = sys_get_temp_dir() . '/' . uniqid('frontastic.test.', true);
        $this->filesystem->mkdir($path, 0700);
        $this->removeFilesOnTearDown[] = $path;
        return $path;
    }

    private function assertTheOutputDirectoryMatchesTheFixture(string $outputDirectory, string $fixtureName): void
    {
        $fixtureDirectory = __DIR__ . '/_fixtures/sitemaps/' . $fixtureName;

        $this->assertDirectoryExists($fixtureDirectory, 'unknown fixture ' . $fixtureName);
        $this->assertDirectoryExists($outputDirectory, 'the output directory does not exist');

        $finder = new Finder();
        $fixtureIterator = $finder->files()->in($fixtureDirectory);
        foreach ($fixtureIterator as $fixtureFile) {
            $fixtureFilename = $fixtureFile->getRealPath();
            $outputFilename = $outputDirectory . '/' . $fixtureFile->getRelativePathname();

            $this->assertFileExists($outputFilename);

            $fixtureContent = file_get_contents($fixtureFilename);
            $fixtureContent = str_replace('__CURRENT_DATE__', date('Y-m-d'), $fixtureContent);

            $outputContent = file_get_contents($outputFilename);
            $this->assertEquals($fixtureContent, $outputContent);
        }
    }

    private function givenTheNodeAndPageFixture(string $fixtureName): void
    {
        $nodeFilename = __DIR__ . '/_fixtures/nodes/' . $fixtureName . '.json';
        $this->assertFileIsReadable($nodeFilename, 'Unknown node fixture: ' . $fixtureName);

        $pageFilename = __DIR__ . '/_fixtures/pages/' . $fixtureName . '.json';
        $this->assertFileIsReadable($nodeFilename, 'Unknown page fixture: ' . $pageFilename);

        $container = self::getContainer();
        $container->get(NodeService::class)->replicate(json_decode(file_get_contents($nodeFilename), true));
        $container->get(PageService::class)->replicate(json_decode(file_get_contents($pageFilename), true));
    }
}

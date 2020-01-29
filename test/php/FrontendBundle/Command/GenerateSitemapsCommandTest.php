<?php

namespace Frontastic\Catwalk\FrontendBundle\Command;

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
        ]);

        $this->assertTheOutputDirectoryMatchesTheFixture($outputDirectory, 'empty');
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
            $this->assertFileEquals($fixtureFilename, $outputFilename);
        }
    }
}

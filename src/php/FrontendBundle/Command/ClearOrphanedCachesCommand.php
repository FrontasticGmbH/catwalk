<?php
namespace Frontastic\Catwalk\FrontendBundle\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

/**
 * This command clears the symfony caches of older versions, which is necessary on the deploy-hosts and has no effect
 * on production catwalks.
 */
#[AsCommand(
    name: 'frontastic:clear:orphaned-system-caches',
    description: 'Removes orphaned system caches from older software versions which is necessary for the deploy-hosts'
)]
class ClearOrphanedCachesCommand extends Command
{
    public function __construct(
        private readonly string $kernelProjectDir,
        private readonly string $cacheDir,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // The removed %kernel.name% parameter was derived from the base dir name
        if ('catwalk' === basename($this->kernelProjectDir)) {
            $rootDir = realpath($this->kernelProjectDir . '/../../');
        } else {
            $rootDir = realpath($this->kernelProjectDir . '/../');
        }

        $filesystem = new Filesystem();
        $finder = new Finder();

        $cacheDir = $this->cacheDir;
        foreach ($finder->directories()->in($cacheDir)->depth(1) as $dir) {
            if (2 !== (count($parts = explode('@', $dir->getBasename())))) {
                continue;
            }
            $component = basename($dir->getPath());
            $environment = null;
            if (file_exists("{$rootDir}/{$component}/environment")) {
                $environment = parse_ini_file("{$rootDir}/{$component}/environment");
            } elseif (file_exists("{$rootDir}/paas/{$component}/environment")) {
                $environment = parse_ini_file("{$rootDir}/paas/{$component}/environment");
            }

            if (false === isset($environment['version'])) {
                continue;
            }

            if ($parts[1] !== $environment['version']) {
                $output->writeln("Deleting '{$dir->getRealPath()}' for component '{$component}'");
                $filesystem->remove($dir->getRealPath());
            }
        }

        return 0;
    }
}

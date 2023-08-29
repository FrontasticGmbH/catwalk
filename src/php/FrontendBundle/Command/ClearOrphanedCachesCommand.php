<?php
namespace Frontastic\Catwalk\FrontendBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

/**
 * This command clears the symfony caches of older versions, which is necessary on the deploy-hosts and has no effect
 * on production catwalks.
 */
class ClearOrphanedCachesCommand extends Command
{
    use ContainerAwareTrait;

    protected function configure()
    {
        $this
            ->setName('frontastic:clear:orphaned-system-caches')
            ->setDescription(
                'Removes orphaned system caches from older software versions which is necessary for the deploy-hosts'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $container = $this->container;

        if ('catwalk' === $container->getParameter('kernel.name')) {
            $rootDir = realpath($container->getParameter('kernel.project_dir') . '/../../');
        } else {
            $rootDir = realpath($container->getParameter('kernel.project_dir') . '/../');
        }

        $filesystem = new Filesystem();
        $finder = new Finder();

        $cacheDir = $container->getParameter('cache_dir');
        foreach ($finder->directories()->in($cacheDir)->depth(1) as $dir) {
            if (2 !== (sizeof($parts = explode('@', $dir->getBasename())))) {
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

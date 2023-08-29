<?php

namespace Frontastic\Catwalk\FrontendBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class CreateBundleCommand extends Command
{
    use ContainerAwareTrait;

    protected function configure()
    {
        $this
            ->setName('frontastic:create:bundle')
            ->setDescription('Creates a basic bundle follow the Frontastic naming schema')
            // @TODO: Add option --with-controller
            ->addArgument(
                'bundleName',
                InputArgument::REQUIRED,
                'BundleName'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $context = $this->container->get('Frontastic\Catwalk\ApiCoreBundle\Domain\ContextService')->getContext();
        $bundleName = $input->getArgument('bundleName');

        if (!preg_match('(^[A-Za-z]+$)', $bundleName)) {
            throw new \OutOfBoundsException("Invalid bundle name $bundleName – must match (^[A-Za-z]+$)");
        }
        $bundleName = str_replace('Bundle', '', $bundleName) . 'Bundle';

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator(
                __DIR__ . '/../../../bundle',
                \RecursiveDirectoryIterator::SKIP_DOTS
            ),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        $destination = $this->container->getParameter('kernel.project_dir') . '/src/php/' . $bundleName;
        mkdir($destination, 0755, true);

        $namespace = ucfirst(strtolower($context->customer->name));
        foreach ($iterator as $item) {
            $destinationName =
                $destination . '/' . $this->fixNames($iterator->getSubPathName(), $namespace, $bundleName);

            if ($item->isDir()) {
                mkdir($destinationName);
            } else {
                file_put_contents(
                    $destinationName,
                    $this->fixNames(file_get_contents($item), $namespace, $bundleName)
                );
            }
        }

        $bundleDefinitionFile = $this->container->getParameter('kernel.project_dir') . '/config/bundles.php';
        $bundles = [];
        if (file_exists($bundleDefinitionFile)) {
            $bundleObjects = include($bundleDefinitionFile);

            foreach ($bundleObjects as $bundle) {
                $bundles[] = get_class($bundle);
            }
        }
        $bundles[] = $namespace . '\\' . $bundleName . '\\' . $namespace . $bundleName;

        file_put_contents(
            $bundleDefinitionFile,
            "<?php\n\nreturn [\n    new " . implode("(),\n    new ", $bundles) . "(),\n];\n"
        );

        // Clear Caches
        $cacheClearer = $this->container->get('cache_clearer');
        $cacheClearer->clear($this->container->getParameter('kernel.cache_dir'));

        return 0;
    }

    private function fixNames(string $input, string $namespace, string $bundleName): string
    {
        return str_replace(
            [
                'Acme',
                'Example',
            ],
            [
                $namespace,
                str_replace('Bundle', '', $bundleName),
            ],
            $input
        );
    }
}

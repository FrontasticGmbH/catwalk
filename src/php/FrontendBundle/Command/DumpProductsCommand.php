<?php

namespace Frontastic\Catwalk\FrontendBundle\Command;

use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\ProductQuery;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class DumpProductsCommand extends Command
{
    use ContainerAwareTrait;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('frontastic:debug:dump-products')
            ->setDescription('Dump products found in the commerce back-end')
            ->addOption(
                'category',
                'c',
                InputOption::VALUE_REQUIRED,
                'Limit to a category ID'
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $context = $this->container->get('Frontastic\Catwalk\ApiCoreBundle\Domain\ContextService')->getContext();

        $productSearchApi = $this->container
            ->get('Frontastic\Common\ProductSearchApiBundle\Domain\ProductSearchApiFactory')
            ->factor(
                $context->project
            );

        $output->write(
            json_encode(
                $productSearchApi
                    ->query(
                        new ProductQuery([
                            'locale' => $context->locale,
                            'category' => $input->getOption('category'),
                        ])
                    )
                    ->wait()
            )
        );
        $output->writeln('');

        return 0;
    }
}

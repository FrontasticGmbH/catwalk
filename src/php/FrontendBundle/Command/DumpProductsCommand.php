<?php

namespace Frontastic\Catwalk\FrontendBundle\Command;

use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\ProductQuery;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DumpProductsCommand extends ContainerAwareCommand
{
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
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $context = $this->getContainer()->get('Frontastic\Catwalk\ApiCoreBundle\Domain\ContextService')->getContext();

        $productSearchApi = $this->getContainer()
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
    }
}

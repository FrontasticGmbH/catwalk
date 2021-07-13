<?php

namespace Frontastic\Catwalk\FrontendBundle\Command;

use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\CategoryQuery;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DumpCategoriesCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('frontastic:debug:dump-categories')
            ->setDescription('Dump categories found in the commerce back-end');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $context = $this->getContainer()->get('Frontastic\Catwalk\ApiCoreBundle\Domain\ContextService')->getContext();

        $productApi = $this->getContainer()->get('Frontastic\Common\ProductApiBundle\Domain\ProductApiFactory')->factor(
            $context->customer
        );

        $output->write(
            json_encode($productApi->getCategories(
                new CategoryQuery([
                    'locale' => $context->locale,
                ])
            ))
        );
        $output->writeln('');

        return 0;
    }
}

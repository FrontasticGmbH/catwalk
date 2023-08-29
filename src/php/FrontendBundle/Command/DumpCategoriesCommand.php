<?php

namespace Frontastic\Catwalk\FrontendBundle\Command;

use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\CategoryQuery;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class DumpCategoriesCommand extends Command
{
    use ContainerAwareTrait;

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
        $context = $this->container->get('Frontastic\Catwalk\ApiCoreBundle\Domain\ContextService')->getContext();

        $productApi = $this->container->get('Frontastic\Common\ProductApiBundle\Domain\ProductApiFactory')->factor(
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

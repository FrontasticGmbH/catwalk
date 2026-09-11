<?php

namespace Frontastic\Catwalk\FrontendBundle\Command;

use Frontastic\Catwalk\ApiCoreBundle\Domain\ContextService;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\CategoryQuery;
use Frontastic\Common\ProductApiBundle\Domain\ProductApiFactory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'frontastic:debug:dump-categories', description: 'Dump categories found in the commerce back-end')]
class DumpCategoriesCommand extends Command
{
    public function __construct(
        private readonly ContextService $contextService,
        private readonly ProductApiFactory $productApiFactory,
    ) {
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $context = $this->contextService->getContext();

        $productApi = $this->productApiFactory->factor(
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

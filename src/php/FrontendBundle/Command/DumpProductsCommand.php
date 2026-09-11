<?php

namespace Frontastic\Catwalk\FrontendBundle\Command;

use Frontastic\Catwalk\ApiCoreBundle\Domain\ContextService;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\ProductQuery;
use Frontastic\Common\ProductSearchApiBundle\Domain\ProductSearchApiFactory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'frontastic:debug:dump-products', description: 'Dump products found in the commerce back-end')]
class DumpProductsCommand extends Command
{
    public function __construct(
        private readonly ContextService $contextService,
        private readonly ProductSearchApiFactory $productSearchApiFactory,
    ) {
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
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
        $context = $this->contextService->getContext();

        $productSearchApi = $this->productSearchApiFactory
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

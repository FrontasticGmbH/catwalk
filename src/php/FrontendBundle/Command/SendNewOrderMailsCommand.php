<?php

namespace Frontastic\Catwalk\FrontendBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Yaml\Yaml;

class SendNewOrderMailsCommand extends Command
{
    use ContainerAwareTrait;

    protected function configure()
    {
        $this
            ->setName('frontastic:order:send')
            ->setDescription('Sends all new orders per mail to the defiend address')
            ->addArgument('mail', InputArgument::REQUIRED, 'Target mail address');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $to = $input->getArgument('mail');

        $context = $this->container->get('Frontastic\Catwalk\ApiCoreBundle\Domain\ContextService')->getContext();
        $cartApi = $this->container->get('Frontastic\Common\CartApiBundle\Domain\CartApiFactory')->factor(
            $context->project
        );

        $orders = $cartApi->getNewOrders();
        foreach ($orders as $order) {
            mail(
                $to,
                'New Order: ' . $order->orderId,
                Yaml::dump(json_decode(json_encode($order), true), 10, 2)
            );
        }

        return 0;
    }
}

<?php

namespace Frontastic\Catwalk\FrontendBundle\Command;

use Frontastic\Common\HttpClient;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AnnounceReleaseCommand extends ContainerAwareCommand
{
    const TIDEWAYS_URL = 'https://app.tideways.io/api/events';

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('frontastic:release:announce')
            ->setDescription('Announce the roll out of a release')
            ->addArgument(
                'release-tag',
                InputArgument::REQUIRED,
                'The tag to announce'
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->announceToTideways($input, $output);
        $this->announceToNewrelic($input, $output);

        return 0;
    }

    protected function announceToTideways(InputInterface $input, OutputInterface $output): void
    {
        if (!($apiKey = getenv('tideways_key')) || trim($apiKey) === '') {
            $output->writeln('<error>No tideways key available, not announcing release.</error>');
            return;
        }

        $response = $this->getContainer()->get(HttpClient::class)
            ->request(
                'POST',
                self::TIDEWAYS_URL,
                json_encode((object)[
                    'apiKey' => $apiKey,
                    'name' => $input->getArgument('release-tag'),
                    'type' => 'release',
                    'environment' => getenv('environment'),
                    'compareAfterMinutes' => 90,
                ])
            );

        if ($response->status < 400 && $response->status > 199) {
            $output->writeln('<info>Announced release successfully</info>');
            return;
        }

        $output->writeln('<error>Error announcing release:</error>');
        $output->writeln(sprintf('<error>HTTP/%s: %s</error>', $response->status, $response->body));
    }

    protected function announceToNewrelic(InputInterface $input, OutputInterface $output): void
    {
        if (false === extension_loaded('newrelic')) {
            $output->writeln('<info>No newrelic extension installed, not announcing release.</info>');
            return;
        }

        $releaseTag = $input->getArgument('release-tag');

        newrelic_record_custom_event("New Release: ${releaseTag}", [
            'name' => $releaseTag,
            'type' => 'release',
            'environment' => getenv('environment'),
        ]);

        $output->writeln('<info>Announced release successfully</info>');
    }
}

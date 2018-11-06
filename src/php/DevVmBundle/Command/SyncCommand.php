<?php
namespace Frontastic\Catwalk\DevVmBundle\Command;

use Frontastic\Catwalk\DevVmBundle\Domain\Archive;
use Frontastic\Common\HttpClient\Signing;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

class SyncCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('frontastic:vm:catwalk-sync')
            ->setDescription('Synchronizes locale dev catwalk data into a cloud instance.');

        $this
            ->addOption(
                'backup',
                'b',
                InputOption::VALUE_OPTIONAL,
                'Receive a backup archive of the current catwalk.',
                false
            )
            ->addOption(
                'vm',
                'vm',
                InputOption::VALUE_NONE,
                'Provision the development VirtualMachine.'
            );

        $this
            ->addArgument(
                'archive',
                InputArgument::OPTIONAL,
                'Optional backup archive that should be restored on the cloud instance.'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $syncDirectory = realpath(sprintf(
            '%s/public/assets/',
            $this->getContainer()->getParameter('kernel.root_dir')
        ));

        if ($restoreFile = $input->getArgument('archive')) {
            $archive = Archive::createFromExistingArchive($restoreFile);
        } else {
            $archive = Archive::createFromDirectory($syncDirectory);
        }

        $projectFile = $this->getContainer()->getParameter('frontastic.project_file');
        $yaml = Yaml::parse(file_get_contents($projectFile), Yaml::PARSE_OBJECT_FOR_MAP);

        $uri = sprintf(
            '%s://%s',
            parse_url($yaml->previewUrl, PHP_URL_SCHEME),
            parse_url($yaml->previewUrl, PHP_URL_HOST)
        );

        $parameters = [];
        if (false !== $input->getOption('backup')) {
            $parameters['backup'] = 1;
        }

        if (false === $input->getOption('vm')) {
            $uri = preg_replace(['(^http://)', '(\.local$)'], ['https://', ''], $uri);
        }

        $customerSecret = getenv('secret');

        /** @var \Frontastic\Common\HttpClient\Signing $httpClient */
        $httpClient = new Signing(
            $this->getContainer()->get('Frontastic\Common\HttpClient\Stream'),
            $customerSecret
        );

        $response = $httpClient->request(
            'POST',
            "{$uri}/devvm/sync?" . http_build_query($parameters),
            $archive->dump()
        );

        if ($response->status < 400) {
            $output->writeln("Successfully synced local catwalk changes to '{$uri}'.");
        } else {
            $output->writeln("<error>Failed to sync local catwalk changes to '{$uri}'.</error>");
        }

        if (false !== $input->getOption('backup')) {
            $filename = ($input->getOption('backup') ?:
                    sprintf(
                        '%s-%s',
                        date('YmdHis'),
                        parse_url($uri, PHP_URL_HOST)
                    )) . '.zip';

            $body = json_decode($response->body);
            Archive::createFromBinaryData($body->backup, $filename);

            $output->writeln("Successfully stored backup of catwalk in  '{$filename}'.");
        }
    }
}

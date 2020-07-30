<?php
namespace Frontastic\Catwalk\FrontendBundle\Command;

use Cron\CronExpression;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Process\Process;

class CronCommand extends ContainerAwareCommand
{
    const REGEXP = '(^(\S+\s+\S+\s+\S+\s+\S+\s+\S+)\s+(.*)$)';

    protected function configure(): void
    {
        $this
            ->setName('frontastic:cron:run')
            ->setDescription('Runs project specific cron jobs');
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity) Batch code
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $projectDir = dirname(dirname(realpath($_SERVER['SCRIPT_NAME'])));
        $crontabFile = $projectDir . '/config/crontab';

        if (false === file_exists($crontabFile)) {
            return;
        }

        $verbose = (bool) $input->getOption('verbose');

        $lines = array_filter(array_map('trim', file($crontabFile)));

        $commands = [];
        foreach ($lines as $line) {
            if (0 === preg_match(self::REGEXP, $line, $match)) {
                continue;
            }
            if (CronExpression::factory($match[1])->isDue()) {
                $commands[] = $match[2];
            }
        }

        $logger = $this->getContainer()->get('logger');
        foreach ($commands as $command) {
            $verbose && $output->writeln("Running: {$command}");
            $process = new Process($command, $projectDir);
            $process->setTimeout(300);
            $process->run();

            $processOutput = trim($process->getOutput());
            $processErrorOutput = trim($process->getErrorOutput());
            $result =sprintf(
                'Cronjob %s %s: STDOUT: %s STDERR: %s',
                $command,
                $process->isSuccessful() ? 'succeeded' : 'failed',
                $processOutput,
                $processErrorOutput
            );
            $verbose && $output->writeln($result);

            if ($processOutput || $processErrorOutput || !$process->isSuccessful()) {
                $logger->warn($result);
            }
        }
    }
}

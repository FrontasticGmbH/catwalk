<?php
namespace Frontastic\Catwalk\FrontendBundle\Command;

use Cron\CronExpression;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Process\Process;

class CronCommand extends Command
{
    use ContainerAwareTrait;

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
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $projectDir = dirname(dirname(realpath($_SERVER['SCRIPT_NAME'])));
        $crontabFile = $projectDir . '/config/crontab';

        if (false === file_exists($crontabFile)) {
            return 0;
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

        foreach ($commands as $command) {
            try {
                $this->processCommand($output, $verbose, $command, $projectDir);
            } catch (\Exception $e) {
                $this
                    ->container
                    ->get('logger')
                    ->alert(
                        sprintf(
                            "Cronjob '%s' failed:\nMESSAGE: %s\nSTACKTRACE:\n%s",
                            $command,
                            $e->getMessage(),
                            $e->getTraceAsString()
                        )
                    );

                return 1;
            }
        }

        return 0;
    }

    protected function processCommand(
        OutputInterface $output,
        bool $verbose,
        string $command,
        string $projectDir
    ): void {
        $verbose && $output->writeln("Running: {$command}");

        $process = new Process($command, $projectDir);
        $process->setTimeout(600);
        $process->run();

        $processOutput = trim($process->getOutput());
        $processErrorOutput = trim($process->getErrorOutput());
        $result = sprintf(
            'Cronjob %s (%d, %s) %s: STDOUT: %s STDERR: %s',
            $command,
            $process->getExitCode(),
            $process->getExitCodeText(),
            $process->isSuccessful() ? 'succeeded' : 'failed',
            $processOutput,
            $processErrorOutput
        );
        $verbose && $output->writeln($result);

        if ($processOutput || $processErrorOutput || !$process->isSuccessful() || $process->getExitCode() != 0) {
            $this
                ->container
                ->get('logger')
                ->warn($result);
        }
    }
}

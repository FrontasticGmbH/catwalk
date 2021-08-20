<?php

namespace Frontastic\Catwalk\NextJsBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use PhpParser\ParserFactory;
use PhpParser\NodeTraverser;
use Frontastic\Catwalk\NextJsBundle\ApiTypeParser\Visitor;

class ConvertApiTypesToTypescriptCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('frontastic:nextjs:convert-types');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $fs = new Filesystem();
        $finder = new Finder();
        $projectDir = $this->getContainer()->get('kernel')->getProjectDir();
        $apiDir = join(DIRECTORY_SEPARATOR, array($projectDir, 'vendor', 'frontastic', 'catwalk', 'src', 'php', 'NextJsBundle', 'Domain', 'Api'));
        $finder->files('*.php')->in($apiDir);

        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        $traverser = new NodeTraverser();

        foreach ($finder as $file) {
            $visitor = new Visitor();
            $traverser->addVisitor($visitor);
            $code = $file->getContents();
            try {
                $ast = $parser->parse($code);
                $traverser->traverse($ast);
                if ($visitor->getOutput()) {
                    //$output->writeln("\n\n".$visitor->getOutput()."\n\n");
                    $targetFile = join(DIRECTORY_SEPARATOR, array($apiDir, str_replace('.php', '.d.ts', $file->getFilename())));
                    $fs->dumpFile($targetFile, $visitor->getOutput());
                    $output->writeln('created interface ' . $targetFile);
                }

            } catch (\ParseError $e) {
                $output->writeln('Parse error: ' .$e->getMessage());
            }
        }
        return 0;
    }
}

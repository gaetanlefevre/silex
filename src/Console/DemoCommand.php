<?php

namespace Johndodev\Console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Démo
 */
class DemoCommand extends AbstractCommand
{
    protected function configure()
    {
        $this->setName('johndodev:hello')->setDescription('Hello world');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Hello world');
    }
}

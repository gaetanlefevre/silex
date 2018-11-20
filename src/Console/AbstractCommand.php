<?php

namespace Johndodev\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Démo
 */
class AbstractCommand extends Command
{
    public function getContainer()
    {
        $application = $this->getApplication();

        if (!$application instanceof Console) {
            throw new \Exception('Your console application must be an instance of Johndodev\Console\Console in order to have access to the Container');
        }

        return $this->getApplication()->getContainer();
    }
}

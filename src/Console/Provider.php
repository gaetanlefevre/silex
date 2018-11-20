<?php

namespace Johndodev\Console;

use Johndodev\Provider\AbstractProvider;
use Symfony\Component\Console\Application;

/**
 * Ajoute la commande de démo :)
 */
class Provider extends AbstractProvider
{
    public function registerCommand(Application $console)
    {
        $console->add(new DemoCommand());
    }
}

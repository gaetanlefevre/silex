<?php

namespace Johndodev\Console;

use Symfony\Component\Console\Application;

/**
 * Interface CommandProviderInterface
 */
interface CommandProviderInterface
{
    /**
     * Add your commands in the console
     * @param Application $console
     * @return void
     */
    public function registerCommand(Application $console);
}

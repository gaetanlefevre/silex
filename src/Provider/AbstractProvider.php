<?php

namespace Johndodev\Provider;

use Pimple\Container;
use Symfony\Component\Console\Application;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class AbstractProvider implements ProviderInterface
{
    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Container $pimple A container instance
     */
    public function register(Container $pimple)
    {
        // TODO: Implement register() method.
    }

    /**
     * Add your commands in the console
     * @param Application $console
     */
     public function registerCommand(Application $console)
     {
         // TODO: Implement registerCommand() method.
     }

     public function subscribe(Container $app, EventDispatcherInterface $dispatcher)
     {
         // TODO: Implement subscribe() method.
     }
 }

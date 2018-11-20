<?php

namespace Johndodev\Console;

use Johndodev\Provider\AbstractProvider;
use Pimple\Container;
use Silex\Application;

class ConsoleServiceProvider extends AbstractProvider
{
    public function register(Container $container)
    {
        $container['console.name'] = 'App';
        $container['console.version'] = '1';

        $container['console'] = function (Container $container) {
            return new Console($container, $container['console.name'], $container['console.version']);
        };
    }
}

<?php

namespace Johndodev\Provider\Provider;

use Johndodev\Provider\AbstractProvider;
use M6Web\Component\Statsd\Client;
use Pimple\Container;
use Symfony\Bridge\Doctrine\Form\DoctrineOrmExtension;

class StatsdProvider extends AbstractProvider
{
    public function register(Container $container)
    {
        $container['statsd.clients'] = [];

        $container['statsd'] = function(Container $container) {
            return new Client($container['statsd.clients']);
        };
    }
}

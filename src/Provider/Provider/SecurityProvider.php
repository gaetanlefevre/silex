<?php

namespace Johndodev\Provider\Provider;

use Johndodev\Provider\AbstractProvider;
use Johndodev\Security\BcryptEncoder;
use Johndodev\Security\UserProvider;
use Pimple\Container;
use Silex\Api\BootableProviderInterface;
use Silex\Application;
use Symfony\Bridge\Doctrine\Security\User\EntityUserProvider;
use Symfony\Component\Security\Core\User\ChainUserProvider;

class SecurityProvider extends AbstractProvider
{
    public function register(Container $container)
    {
        $container['security.user_chain_provider'] = function(Container $container) {
            $providers = [];

            foreach ($container['security.user_providers']->keys() as $providerName) {
                $providers[] = $container['security.user_providers'][$providerName];
            }

            return new ChainUserProvider($providers);
        };

        $container['security.app_voters'] = [];
        $container['security.user_providers'] = function (Container $container) {
            $providers = new Container();

            foreach ($container['security.providers'] as $providerName => $provider) {
                $providers[$providerName] = function (Container $providers) use ($container, $provider) {
                    return new EntityUserProvider($container['orm.manager_registry'], $provider['class'], $provider['property'], $provider['manager_name']);
                };
            }

            return $providers;
        };

        $container->extend('security.voters', function ($voters, Container $container) {

            foreach ($container['security.app_voters'] as $service) {
                $voters[] = $container[$service];
            }

            return $voters;
        });
    }
}

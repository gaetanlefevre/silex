<?php

namespace Johndodev\Provider\Provider;

use Doctrine\Common\Cache\ArrayCache;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Johndodev\ORM\EntityListenerResolver;
use Johndodev\ORM\ManagerRegistry;
use Johndodev\Provider\AbstractProvider;
use Pimple\Container;
use Silex\Api\BootableProviderInterface;
use Silex\Application;
use Sorien\Logger\DbalLogger;
use Symfony\Bridge\Doctrine\Validator\DoctrineInitializer;

class ORMProvider extends AbstractProvider implements BootableProviderInterface
{
    public function register(Container $container)
    {
        $container['orm.custom_numeric_functions'] = [];
        $container['orm.types'] = [];
        $container['orm.mapping'] = [];

        // Correspondance class => serviceName des listeners des entity...
        $container['orm.entity_listeners'] = [];

        // ...pour le résolver
        $container['orm.entity_listener_resolver'] = function($container) {
            return new EntityListenerResolver($container, $container['orm.entity_listeners']);
        };

        // le cache à utiliser, préférez un memcache ou redis en prod
        $container['orm.cache'] = function (Container $container) {
            return new ArrayCache();
        };

        // Managers
        //----------
        $container['init_managers'] = $container->protect(function () use ($container) {
            static $inited = false;

            if ($inited) {
                return;
            }

            $inited = true;

            // types
            foreach ($container['orm.types'] as $name => $class) {
                \Doctrine\DBAL\Types\Type::addType($name, $class);
            }

            // define an entityManager called em.{name} for each managers in config
            foreach ($container['orm.managers'] as $name => $conf) {
                $container['em.'.$name] = function(Container $container) use ($name, $conf) {
                    $config = Setup::createAnnotationMetadataConfiguration($conf['entity_paths'], $container['debug'], $container['app.cache_dir'].'doctrine/proxy', $container['orm.cache'], false);

                    // set custom entityListenerResolver
                    $config->setEntityListenerResolver($container['orm.entity_listener_resolver']);

                    // add dql functions
                    foreach ($container['orm.custom_numeric_functions'] as $f => $class) {
                        $config->addCustomNumericFunction($f, $class);
                    }

                    $connectionName = $conf['connection'];
                    $em = EntityManager::create($container['dbs'][$connectionName], $config);

                    // mapping
                    foreach ($container['orm.mapping'] as $k => $v) {
                        $em->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping($k, $v);
                    }

                    return $em;
                };

                // for the first one, define a default em called "em"
                if (!isset($container['em'])) {
                    $container['em'] = function(Container $container) use ($name) {
                        return $container['em.'.$name];
                    };
                }
            }
        });


        // Manager Registry
        //------------------

        // save the managers names in an array to facilitate instanciation of ManagerRegistry
        $container['orm.manager_registry.managers'] = function(Container $container) {
            $container['init_managers']();

            $managers = ['em' => 'em'];

            foreach ($container['orm.managers'] as $name => $conf) {
                $managers['em.'.$name] = 'em.'.$name;
            }

            return $managers;
        };

        $container['orm.manager_registry'] = function(Container $container) {
            $managerRegistry = new ManagerRegistry(null, [], $container['orm.manager_registry.managers'], null, 'em', 'Doctrine\ORM\Proxy\Proxy');
            $managerRegistry->setContainer($container);
            return $managerRegistry;
        };

        // Validation
        if (isset($container['validator.object_initializers'])) {
            $container->extend('validator.object_initializers', function($initializers, Container $container) {
                $initializers[] = new DoctrineInitializer($container['orm.manager_registry']);
                return $initializers;
            });
        }
    }

    /**
     * Dynamic load of entity managers defined in $app['orm.managers']
     * @param Application $app
     */
    public function boot(Application $app)
    {
        $app['init_managers']();
    }
}

<?php

namespace Johndodev\Provider\Provider;

use Johndodev\Provider\AbstractProvider;
use Pimple\Container;
use Silex\Provider\FormServiceProvider;
use Symfony\Bridge\Doctrine\Form\DoctrineOrmExtension;

class FormProvider extends AbstractProvider
{
    public function register(Container $container)
    {
        // pour les types as services
        $container['form.types.services'] = [];

        // le form Provider de silex
        $container->register(new FormServiceProvider());

        // ajoute le type entity
        $container->extend('form.extensions', function ($formExtensions, $container) {
            $formExtensions[] = new DoctrineOrmExtension($container['orm.manager_registry']);
            return $formExtensions;
        });

        // ajoute les types définis en tant que services
        $container->extend('form.types', function($types, Container $container) {
            foreach ($container['form.types.services'] as $serviceId) {
                $types[] = $container[$serviceId];
            }

            return $types;
        });
    }
}

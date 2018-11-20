<?php

namespace Johndodev\Provider\Provider;

use Johndodev\Provider\AbstractProvider;
use Pimple\Container;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntityValidator;

/**
 * Ajoute le UniqueEntityValidator
 */
class ValidatorProvider extends AbstractProvider
{
    public function register(Container $container)
    {
        if (!isset($container['validator.validator_service_ids'])) {
            $container['validator.validator_service_ids'] = array();
        }

        $container['validator.validator_service_ids'] = array_merge($container['validator.validator_service_ids'], array('doctrine.orm.validator.unique' => 'doctrine.orm.validator.unique'));

        $container['doctrine.orm.validator.unique'] = function(Container $container) {
            return new UniqueEntityValidator($container['orm.manager_registry']);
        };
    }
}

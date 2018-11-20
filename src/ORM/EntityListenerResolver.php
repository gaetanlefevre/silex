<?php

namespace Johndodev\ORM;

use Doctrine\ORM\Mapping\DefaultEntityListenerResolver;
use Pimple\Container;

class EntityListenerResolver extends DefaultEntityListenerResolver
{
    /**
     * @var Container
     */
    private $container;

    /**
     * key = class, value = listener service name
     * @var array
     */
    private $entityListeners;

    /**
     * @param Container $container
     * @param array $entityListeners
     */
    public function __construct(Container $container, $entityListeners = [])
    {
        $this->container = $container;
        $this->entityListeners = $entityListeners;
    }

    /**
     * @inheritdoc
     */
    public function resolve($className)
    {
        if (isset($this->entityListeners[$className])) {
            return $this->container[$this->entityListeners[$className]];
        }

        return parent::resolve($className);
    }
}

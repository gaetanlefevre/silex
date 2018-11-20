<?php

namespace Johndodev\ORM;

use Doctrine\Common\Persistence\AbstractManagerRegistry;
use Pimple\Container;

class ManagerRegistry extends AbstractManagerRegistry
{
    protected $container;

    protected function getService($name)
    {
        return $this->container[$name];
    }

    protected function resetService($name)
    {
        unset($this->container[$name]);
    }

    public function getAliasNamespace($alias)
    {
        throw new \BadMethodCallException('Namespace aliases not supported.');
    }

    public function setContainer(Container $container)
    {
        $this->container = $container;
    }
}

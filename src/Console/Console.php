<?php

namespace Johndodev\Console;


use Symfony\Component\Console\Application;
use Pimple\Container;

class Console extends Application
{
    /**
     * @var Container
     */
    private $container;

    public function __construct(Container $container, $name = 'UNKNOWN', $version = 'UNKNOWN')
    {
        parent::__construct($name, $version);

        $this->container = $container;
    }

    /**
     * @return Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param Container $container
     */
    public function setContainer($container)
    {
        $this->container = $container;
    }
}

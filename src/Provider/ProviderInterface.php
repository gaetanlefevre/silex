<?php

namespace Johndodev\Provider;

use Johndodev\Console\CommandProviderInterface;
use Pimple\ServiceProviderInterface;
use Silex\Api\EventListenerProviderInterface;

interface ProviderInterface extends ServiceProviderInterface, EventListenerProviderInterface, CommandProviderInterface
{
}

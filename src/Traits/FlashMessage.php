<?php

namespace Johndodev\Traits;
use Johndodev\Twig\BootstrapAlerts;

/**
 * Flash Messages with bootstrap (work with BoostrapAlertsExtension)
 */
Trait FlashMessage
{
    public function addSuccessMessage($message)
    {
        $this->addMessage(BootstrapAlerts::GREEN, $message);
    }

    public function addWarningMessage($message)
    {
        $this->addMessage(BootstrapAlerts::YELLOW, $message);
    }

    public function addErrorMessage($message)
    {
        $this->addMessage(BootstrapAlerts::RED, $message);
    }

    public function addInfoMessage($message)
    {
        $this->addMessage(BootstrapAlerts::BLUE, $message);
    }

    protected function addMessage($type, $message)
    {
        $this['session']->getFlashBag()->add($type, $message);
    }
}

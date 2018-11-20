<?php

namespace Johndodev\Traits;

Trait RoutingTrait
{
    public function redirectToRoute($route, array $parameters = array(), $status = 302)
    {
        return $this->redirect($this->url($route, $parameters), $status);
    }
}

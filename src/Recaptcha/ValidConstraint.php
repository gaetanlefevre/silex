<?php

namespace Johndodev\Recaptcha;

use Symfony\Component\Validator\Constraint;

class ValidConstraint extends Constraint
{
    public $message = 'Invalid captcha';
}

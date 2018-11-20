<?php

namespace Johndodev\Recaptcha;

use ReCaptcha\ReCaptcha;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ValidConstraintValidator extends ConstraintValidator
{
    /**
     * @var ReCaptcha
     */
    private $reCaptcha;
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var
     */
    private $enabled;

    public function __construct(ReCaptcha $reCaptcha, RequestStack $requestStack, $enabled)
    {
        $this->reCaptcha = $reCaptcha;
        $this->requestStack = $requestStack;
        $this->enabled = $enabled;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$this->enabled) {
            return;
        }

        $request = $this->requestStack->getCurrentRequest();
        $value = $request->request->get('g-recaptcha-response');

        if (!$value || !$this->reCaptcha->verify($value, $request->getClientIp())->isSuccess()) {
            // $form->addError(new FormError('Captcha error'));
            $this->context->addViolation('Captcha error');
        }
    }
}

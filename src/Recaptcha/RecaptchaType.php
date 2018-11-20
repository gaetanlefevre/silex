<?php

namespace Johndodev\Recaptcha;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecaptchaType extends AbstractType
{
    /**
     * @var string
     */
    private $siteKey;

    public function __construct($siteKey)
    {
        $this->siteKey = $siteKey;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'mapped' => false,
            'label' => false,
            'constraints' => new ValidConstraint(),
        ]);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['recaptcha_site_key'] = $this->siteKey;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return Type\TextType::class;
    }
}

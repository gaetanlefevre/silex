<?php

namespace Johndodev\Recaptcha;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use ReCaptcha\ReCaptcha;

class RecaptchaServiceProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container['recaptcha.secret'] = '';
        $container['recaptcha.site_key'] = '';
        $container['recaptcha.enabled'] = true;

        $container['recaptcha.form_type'] = function (Container $container) {
            return new RecaptchaType($container['recaptcha.site_key']);
        };

        $container['recaptcha.form_validator'] = function (Container $container) {
            return new ValidConstraintValidator($container['recaptcha'], $container['request_stack'], $container['recaptcha.enabled']);
        };

        $container['recaptcha'] = function(Container $container) {
            return new ReCaptcha($container['recaptcha.secret']);
        };

        // add form types
        if (isset($container['form.types'])) {

            $container->extend('form.types', function($types, Container $container) {
                $types[] = $container['recaptcha.form_type'];
                return $types;
            });

            $container->extend('twig.loader.filesystem', function (\Twig_Loader_Filesystem $loader, Container $container) {
                $loader->addPath(__DIR__);
                return $loader;
            });

            $container['twig.form.templates'] = array_merge(['RecaptchaField.html.twig'], $container['twig.form.templates']);
            $container['validator.validator_service_ids'] = array_merge([ValidConstraintValidator::class => 'recaptcha.form_validator'], $container['validator.validator_service_ids']);
        }
    }
}

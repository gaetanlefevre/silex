<?php

namespace Johndodev\Provider\Provider;

use Johndodev\Provider\AbstractProvider;
use Johndodev\Twig\BootstrapAlerts;
use Pimple\Container;
use Silex\Provider\TwigServiceProvider;

class TwigProvider extends AbstractProvider
{
    public function register(Container $container)
    {
        $container->register(new TwigServiceProvider(), [
            'twig.path' => [__DIR__.'/../../View']
        ]);

        // nom des services des extensions twig
        $container['twig.app_extensions'] = [];

        // extend twig
        $container->extend('twig', function(\Twig_Environment $twig, Container $container) {
            $twig->addExtension(new BootstrapAlerts($twig));

            foreach ($container['twig.app_extensions'] as $serviceName) {
                $twig->addExtension($container[$serviceName]);
            }

            return $twig;
        });
    }
}

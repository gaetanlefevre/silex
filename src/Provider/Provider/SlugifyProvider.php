<?php

namespace Johndodev\Provider\Provider;

use Cocur\Slugify\Bridge\Twig\SlugifyExtension;
use Cocur\Slugify\Slugify;
use Johndodev\Provider\AbstractProvider;
use Pimple\Container;

class SlugifyProvider extends AbstractProvider
{
    public function register(Container $container)
    {
        $container['slugify.options']  = [];
        $container['slugify.provider'] = null;

        $container['slugify'] = function (Container $container) {
            return new Slugify($container['slugify.options'], $container['slugify.provider']);
        };

        $container->extend('twig', function (\Twig_Environment $twig, Container $container) {
            $twig->addExtension(new SlugifyExtension($container['slugify']));

            return $twig;
        });
    }
}

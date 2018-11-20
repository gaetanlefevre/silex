<?php

namespace Johndodev;

use Johndodev\Console\CommandProviderInterface;
use Johndodev\Console;
use Johndodev\Provider\Provider\FormProvider;
use Johndodev\Provider\Provider\ORMProvider;
use Johndodev\Provider\Provider\SecurityProvider;
use Johndodev\Provider\Provider\SlugifyProvider;
use Johndodev\Provider\Provider\StatsdProvider;
use Johndodev\Provider\Provider\TwigProvider;
use Johndodev\Provider\Provider\ValidatorProvider;
use Johndodev\Provider\ProviderInterface;
use Johndodev\Recaptcha\RecaptchaServiceProvider;
use Johndodev\Traits\FlashMessage;
use Johndodev\Traits\RoutingTrait;
use Pimple\Container;
use Silex\Application;
use Silex\Provider\AssetServiceProvider;
use Silex\Provider\CsrfServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\HttpFragmentServiceProvider;
use Silex\Provider\LocaleServiceProvider;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\RoutingServiceProvider;
use Silex\Provider\SecurityServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\SwiftmailerServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\VarDumperServiceProvider;
use Silex\Provider\WebProfilerServiceProvider;
use Sorien\Provider\DoctrineProfilerServiceProvider;

abstract class Kernel extends Application
{
    // traits
    //--------
    use Application\TwigTrait;
    use Application\FormTrait;
    use Application\SecurityTrait;
    use Application\SwiftmailerTrait;
    use Application\UrlGeneratorTrait;
    use FlashMessage;
    use RoutingTrait;

    /**
     * Environement
     */
    const ENV_DEV = 'dev';
    const ENV_PROD = 'prod';

    /**
     * @var Console\Console
     */
    private $console;

    /**
     * @param string $env
     * @param bool $debug
     */
    public function __construct($env, $debug)
    {
        parent::__construct();

        $this['env']    = $env;
        $this['debug']  = $debug;
        $this['app.cache_dir'] = $this->getCacheDir();

        ini_set('display_errors', $this['debug']);

        // Services de base
        //-----------------

        // démo command
        $this->register(new Console\Provider());

        // locale (needed for twig form)
        $this->register(new LocaleServiceProvider(), ['locale' => 'fr']);
        $this->register(new TranslationServiceProvider());

        // form - http://symfony.com/doc/current/reference/forms/twig_reference.html
        $this->register(new ValidatorServiceProvider());
        $this->register(new ValidatorProvider());
        $this->register(new CsrfServiceProvider());
        $this->register(new FormProvider());

        // session
        $this->register(new SessionServiceProvider());

        // controllers as services
        $this->register(new ServiceControllerServiceProvider());

        // twig
        $this->register(new TwigProvider());

        // twig render() function
        $this->register(new HttpFragmentServiceProvider());

        // twig path() and url() functions
        $this->register(new RoutingServiceProvider());

        // asset
        $this->register(new AssetServiceProvider());

        // swift
        $this->register(new SwiftmailerServiceProvider());

        // security
        $this->register(new SecurityServiceProvider());
        $this->register(new SecurityProvider());

        // cache TODO

        // monolog
        $this->register(new MonologServiceProvider());

        // ORM
        $this->register(new DoctrineServiceProvider());
        $this->register(new ORMProvider());

        // slugify
        $this->register(new SlugifyProvider());

        // statsd
        $this->register(new StatsdProvider());

        // dump()
        $this->register(new VarDumperServiceProvider());

        // recaptcha
        $this->register(new RecaptchaServiceProvider());

        // statsd  TODO ?

        // console
        $this->register(new Console\ConsoleServiceProvider());

        // les services de l'appli
        //--------------------------
        foreach ($this->getProviders() as $provider) {
            $this->register($provider);
        }

        // web profiler
        if ($env == self::ENV_DEV) {
            $this->register(new WebProfilerServiceProvider());
            $this->register(new DoctrineProfilerServiceProvider());
        }

        // Une fois que tous les providers sont enregistrés, on register les commandes
        $providers = $this->providers;

        $this->extend('console', function (Console\Console $console, Container $container) use ($providers) {
            foreach ($providers as $provider) {
                if ($provider instanceof CommandProviderInterface) {
                    $provider->registerCommand($console);
                }
            }

            return $console;
        });

        // config
        $this->loadConfig();
    }

    /**
     * Les services providers
     * @return ProviderInterface[]
     */
    abstract protected function getProviders();

    /**
     * @return string le répertoire de cache avec le / à la fin
     */
    abstract protected function getCacheDir();

    /**
     * Set config parameters dans le container
     */
    abstract protected function loadConfig();
}

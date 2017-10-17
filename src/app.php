<?php

use Silex\Application;
use Silex\Provider\AssetServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\HttpFragmentServiceProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\ValidatorServiceProvider;

$app = new Application();
$app->register(new ServiceControllerServiceProvider());
$app->register(new AssetServiceProvider());
$app->register(new TwigServiceProvider());
$app->register(new HttpFragmentServiceProvider());
$app->register(new ValidatorServiceProvider());
$app['twig'] = $app->extend('twig', function ($twig, $app) {
    // add custom globals, filters, tags, ...

    return $twig;
});

$app->register(new FormServiceProvider());
$app->register(new Silex\Provider\LocaleServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider(), array(
  'locale_fallbacks' => array('it'),
));

$app->register(new Silex\Provider\CsrfServiceProvider());

$app->register(new Silex\Provider\DoctrineServiceProvider(), [
  'db.options' => [
    'driver'    => 'pdo_mysql',
    'host'      => 'db',
    'dbname'    => 'motork',
    'user'      => 'root',
    'password'  => '',
    'charset'   => 'utf8mb4',
  ]
]);

return $app;

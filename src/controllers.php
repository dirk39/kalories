<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

//Request::setTrustedProxies(array('127.0.0.1'));

//$app->get('/', function () use ($app) {
//    return $app['twig']->render('index.html.twig', array());
//})
//->bind('homepage')
//;

/*
 * Pagina principale con il numero di calorie per giorno
 */
$app->get('/settings', function(Silex\Application $app) {
  /* Recuperare il numero di calorie al giorno inserite dall'utente */
    return $app['twig']->render('settings/index.html.twig', ['calories'=>0]);
})->bind('homepage-settings');

/*
 * Mostra la form per l'inserimento delle calorie
 */
$app->match('/settings/edit', function(Silex\Application $app, Request $request){
  return $app['twig']->render('settings/edit.html.twig', []);
})->method('GET|POST')->bind('settings-edit');

/*
 * Homepage con lista degli elementi mangiati
 */
$app->match('/', function(Silex\Application $app, Request $request){
  /*
   * iniziare con una lista paginata di piatti
   */
  return $app['twig']->render('index.html.twig', []);
});

$app->match('/add-dish', function(Silex\Application $app, Request $request){
  /*
   * Creare form con piatto da aggiungere (validazioni, salvataggio etc)
   */
  return $app['twig']->render('add-dish.html.twig', []);
});


$app->error(function (\Exception $e, Request $request, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    // 404.html, or 40x.html, or 4xx.html, or error.html
    $templates = array(
        'errors/'.$code.'.html.twig',
        'errors/'.substr($code, 0, 2).'x.html.twig',
        'errors/'.substr($code, 0, 1).'xx.html.twig',
        'errors/default.html.twig',
    );

    return new Response($app['twig']->resolveTemplate($templates)->render(array('code' => $code)), $code);
});

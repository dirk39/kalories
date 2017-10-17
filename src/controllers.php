<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;



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
  /*
   * Recuperiamo il valore già a sistema
   */
  $sql = "SELECT * FROM settings LIMIT 1";
  $setting = $app['db']->fetchAssoc($sql, []);


  $form = $app['form.factory']->createBuilder(FormType::class, [])
    ->add('calories', TextType::class, [
      "constraints" => [ new Constraints\NotBlank, new Constraints\Type(['type'=>'numeric']),new Constraints\GreaterThan(['value'=>5])],
      "label" => "calorie al giorno",
      "data" => isset($setting['calories'])? $setting['calories']: null
    ])
    ->add('submit', SubmitType::class,[
      'label' => 'Salva'
    ])
    ->getForm();
  if($request->getMethod() === Request::METHOD_POST)
  {
    $form->handleRequest($request);

    if ($form->isValid()) {
      $data = $form->getData();
      $query = "INSERT INTO settings (calories) VALUES(?)";
      if($setting)
      {
        $query = "UPDATE settings SET calories = :calories WHERE id = :id";
        $data['id'] = $setting['id'];
      }

      $app['db']->executeUpdate($query, $data);

      /* prevedere flash con messaggio success */
    }
  }

  return $app['twig']->render('settings/edit.html.twig', ['form' => $form->createView()]);
})->method('GET|POST')->bind('settings-edit');

/*
 * Homepage con lista degli elementi mangiati
 */
$app->match('/', function(Silex\Application $app, Request $request){
  /*
   * iniziare con una lista di piatti
   */
  $sql = "SELECT * FROM dishes";
  /** @var Doctrine\DBAL\Connection $db */
  $db = $app['db'];
  $dishes = $db->fetchAll($sql);

  return $app['twig']->render('index.html.twig', ['dishes' => $dishes]);
})->bind('homepage');

$app->match('/dishes/{id}/edit', function(Silex\Application $app, Request $request, $id){
  $sql = "SELECT * FROM dishes WHERE id = ?";
  /** @var Doctrine\DBAL\Connection $db */
  $db = $app['db'];
  $dish = $db->fetchAssoc($sql, [$id]);
  if(!$dish)
  {
    return $app->redirect('/');die;
  }

  $form = getDishForm($app, $dish);

  if($request->getMethod() === Request::METHOD_POST) {
    $form->handleRequest($request);

    if ($form->isValid()) {
      $data = $form->getData();
      $data['eating_time'] = $data['eating_time']->format('Y-m-d H:i:s');

      $query = "UPDATE dishes SET dish=:dish, calories=:calories, eating_time=:eating_time WHERE id=:id ";
      $app['db']->executeUpdate($query, $data);

      return $app->redirect('/');
      /* prevedere flash con messaggio success */
    }
  }

  return $app['twig']->render('edit-dish.html.twig', ['form' => $form->createView()]);

})->method("GET|POST")->bind('edit-dish');

$app->match('/dishes/add', function(Silex\Application $app, Request $request){
  $form = getDishForm($app);

  if($request->getMethod() === Request::METHOD_POST) {
    $form->handleRequest($request);

    if ($form->isValid()) {
      $data = $form->getData();
      $data['eating_time'] = $data['eating_time']->format('Y-m-d H:i:s');

      $query = "INSERT INTO dishes (dish, calories, eating_time) VALUES(:dish, :calories, :eating_time)";
      $app['db']->executeUpdate($query, $data);

      return  $app->redirect('/');
      /* prevedere flash con messaggio success */
    }
  }

  return $app['twig']->render('add-dish.html.twig', ['form' => $form->createView()]);
})->method("GET|POST")->bind('add-dish');


$app->match('/dishes/{id}/delete', function(Silex\Application $app, Request $request, $id) {
  $query = "DELETE from dishes WHERE id=:id ";
  $app['db']->executeUpdate($query, ['id' => $id]);
  /** @var  Symfony\Component\Routing\Generator\UrlGenerator $routing */
  $routing = $app['url_generator'];
  $url = $routing->generate('homepage');

  return $app->redirect($url);

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


function getDishForm(\Silex\Application $app, $data = [])
{
  /** @var \Symfony\Component\Form\FormBuilder $form */
  $form = $app['form.factory']->createBuilder(FormType::class, []);
  $form->add('dish', TextType::class, [
    'constraints' => [ new Constraints\NotBlank, new Constraints\Length(['min' => 3])],
    'label' => 'Dish',
    'data' => isset($data['dish'])? $data['dish']: null
  ]);
  $form
    ->add('calories', TextType::class, [
      "constraints" => [ new Constraints\NotBlank, new Constraints\Type(['type'=>'numeric']),new Constraints\GreaterThan(['value'=>5])],
      "label" => "Calories",
      "data" => isset($data['calories'])? $data['calories']: null
    ]);
  $form->add('eating_time', \Symfony\Component\Form\Extension\Core\Type\DateTimeType::class,[
    'label' => 'Eating time',
    'constraints' => [ new Constraints\NotBlank, new Constraints\DateTime ],
    "data" => isset($data['eating_time'])? new DateTime($data['eating_time']): null
  ]);

  if(isset($data['id']))
  {
    $form->add('id', \Symfony\Component\Form\Extension\Core\Type\HiddenType::class,[
      'constraints' => [ new Constraints\NotBlank, new Constraints\Choice([
        $data['id']
      ]) ],
      'data' => $data['id']
    ]);
  }

  $form->add('submit', SubmitType::class, ['label' => 'Save']);

  return $form->getForm();
}

<?php

require SRC . 'base/base.php';
require SRC . 'db/db.php';
require SRC . 'session/session.php';
require SRC . 'logger/logger.php';
require SRC . 'metrics/metrics.php';
require SRC . 'app/app.php';

define('PRINT_DIE', false);

$container = $app->getContainer();

$container['renderer'] = function ($c) {
  $settings = $c->get('settings')['renderer'];
  return new Slim\Views\PhpRenderer($settings['template_path']);
};

// errors
$container['errorHandler'] = function($c){
  return function($request, $response, $exception) use ($c) {

      $message= $exception->getMessage();

      if(DEBUG){
        $extra= ['trace'=>$exception->getTraceAsString()];
      }else{
        $extra= ['trace'=>"en: ".$exception->getFile().", linea ".$exception->getLine()];
      }

      return responseError($c['response'], $message, false);

    };
};
$container['phpErrorHandler'] = function ($container) {
    return function ($request, $response, $error) use ($container) {
        // retrieve logger from $container here and log the error
        $response->getBody()->rewind();
        return $response->withStatus(500)
                        ->withHeader('Content-Type', 'text/html')
                        ->write($error);
    };
};

$container['notFoundHandler'] = function($c){
  return function($request, $response) use ($c)
    {

      $json= array(
        "status"=> 404,
        "developer" => "Ruta no encontrada".PHP_EOL
      );

      return $c['response']
        ->withStatus(404)
        ->withJson($json);
    };
};

<?php

namespace app;
use Respect\Validation\Validator as v;

function validate($params){
  $hashValidator = v::stringType()->noWhitespace();
  $idValidator = v::numeric()->positive();
  $stringValidator= v::stringType()->notEmpty();
  $validators= array(
    'value'=> $stringValidator,
    'id'=> $hashValidator,
  );
  $errors= '';
  foreach ($params as $paramKey=>$paramValue) {
    if(isset($validators[$paramKey])){
      if(!$validators[$paramKey]->validate($paramValue))
        $errors .= $paramKey.',';
    }
  }

  if($errors != '')
    throw new \Exception("No valid parameters: ".trim($errors, ','), 1);
}

function validateMiddleware(){

  return function($req, $resp, $next){
    $params= $req->getParams();
    \app\validate($params);

    return $next($req, $resp);

  };
}
?>

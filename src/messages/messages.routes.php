<?php
namespace messages;
require 'messages.php';

$app->get('/messages/mailtest', function ($request, $response, $args) {
	restrictToKey();
  $queryParams= $request->getQueryParams();
  extract($queryParams);
  $to= isset($to)? $to: EMAIL_DEFAULT_TO;
  $clinica_id= isset($clinica_id)? $clinica_id: '';

  if(sendEmailTest($to, $clinica_id))
    return response($response, true, null, "mail enviado a $to");
  else
    return responseError($response, "no se ha podido enviar el mail a $to");
});
?>

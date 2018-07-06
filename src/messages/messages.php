<?php
namespace messages;

function sendMail($to, $asunto, $cuerpo, $fromName="api", $fromEmail='noreply@citame.click' ){
  $headers = "From: $fromName <$fromEmail>\r\n";
  $headers .= "MIME-Version: 1.0\r\n";
  $headers .= "Content-type: text/html; charset=utf-8\r\n";

  return mail(  $to ,
                '=?UTF-8?B?'.base64_encode($asunto).'?=',
                $cuerpo,
                $headers
            );
}
function getTwillioClient(){
  return new Twilio\Rest\Client(TWILLIO_ACCOUNT_SID, TWILLIO_ACCOUNT_TOKEN);
}
function sendSms($to, $body){

  $client->messages->create($to, [
    'from'=>TWILLIO_FROM,
    'body'=>$body
  ]);
}

function sendEmailTest($to, $clinica_id){

  \db\query("SELECT clinicas.*, recordatorios.* FROM clinicas JOIN recordatorios ON clinicas.recordatorio_id=recordatorios.id WHERE clinicas.id=$clinica_id");
  $clinica= \db\get_array_full()[0];
  $fakeCita= [
    'id'=>\db\query_single("SELECT id FROM citas LIMIT 1"),
    'nombre'=>'Juana',
    'apellidos'=>'De arco',
    'inicio'=>date('c')
  ];
  return \usuarios\sendReminderEmail($fakeCita, $clinica);
}

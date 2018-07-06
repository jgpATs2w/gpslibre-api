<?php

define('PROJECT_ROOT', realpath(__DIR__ . '/..'));
define('PHPUNIT', true);
define('BASE_URI', 'http://localhost/starter-api');
define('PHPUNIT_CLEAN_END', false);


///////////////////////

// Settings to make all errors more obvious during testing
error_reporting(-1);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
date_default_timezone_set('UTC');

require PROJECT_ROOT . '/src/config.php';

require PROJECT_ROOT . '/src/app/app.php';
require PROJECT_ROOT . '/src/base/base.php';
require PROJECT_ROOT . '/src/db/db.php';
require PROJECT_ROOT . '/src/session/session.php';
require PROJECT_ROOT . '/src/logger/logger.php';
require PROJECT_ROOT . '/src/metrics/metrics.php';

if (!class_exists('\PHPUnit_Framework_TestCase') && class_exists('\PHPUnit\Framework\TestCase'))
    class_alias('\PHPUnit\Framework\TestCase', '\PHPUnit_Framework_TestCase');

class AppTestCase extends \PHPUnit_Framework_TestCase{

  function getResponse($json){
    return get_object_vars( json_decode($json) );
  }
  function getReturn ( $json, $ok = 1 ){
      $Response= $this->getResponse($json);

      return $Response['return'];
  }
  function getResultFromJson ( $json ){
      $Response= $this->getResponse($json);

      return $Response['result'];
  }
  function assertResultOK($json, $message=''){
    $Response= $this->getResponse($json);
    return $this->assertSame($Response['result'], 1, $message.': '.$Response['message']);
  }

  function url_exists($url){//TODO check 404!
    $contents= file_get_contents($url);

    if($contents)
      return true;
    return false;
  }

  protected function tearDown()
  {
      if(PHPUNIT_CLEAN_END)
        exec("rm -r ".TMP."*");
  }

}

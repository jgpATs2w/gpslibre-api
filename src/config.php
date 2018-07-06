<?php
if(php_sapi_name() != 'cli' || !empty($_SERVER['REMOTE_ADDR'])){

  // Allow CORS
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Credentials: true");
  header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
  header("Access-Control-Allow-Headers: 'Access-Control-Allow-Headers, Authorization, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers'");
  if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    die();
  }

}

define( 'VERSION_NUMBER', 'v1'  );

//DB parameters
define( 'DBHOST', 'localhost' );
define( 'DBUSER', 'gpslibre_www' );
define( 'DBPASS', 'LjLFNINfKSzyLIuS' );
define( 'DBDB', 'gpslibre');
define( 'DB_MEMORY_LIMIT', 10000);
/**
*
* No need to touch from here
*
**/

date_default_timezone_set( 'Europe/Madrid' );
define( 'LAND', realpath(__DIR__ .'/../..').'/'  );//useful for directories shared between versions
define( 'BASE', realpath(__DIR__ .'/..').'/'  );//include version

define( 'SRC', BASE . 'src/' );

define( 'TMP', "/tmp/" );
define( 'UPLOAD', LAND."/upload/" );


/*
* Debugging, set statically with defined or dinamically with ?debug
*/
// Mock state: use to return quick responses to test integration
if(isset($_GET) && isset($_GET['mock']))
  define( 'MOCK', true );
else
  if(!defined('MOCK'))
    define( 'MOCK', false );
  //allow phpunit to define

// Debug state
if(isset($_GET) && isset($_GET['debug']))
  define( 'DEBUG', true );
else
    if(!defined('DEBUG'))
      define( 'DEBUG', false );//switch to false in production
    //allow phpunit to define

error_reporting(-1);
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);

//Prepare third party tools autoload, configured in composer.json
if(is_file( BASE . 'vendor/autoload.php'))
  require BASE . 'vendor/autoload.php';

//With show parameter in url, this script will show
if(isset($_GET['showConfig'])){
  $defined= get_defined_constants(true);
  require_once SRC."base/base.php";
  prettyprint_r($defined['user']);
  prettyprint_r($_SERVER);
}

//Slim settings
$settings= array(
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header
        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ .'/../view/',
        ],
        // Monolog settings
        'logger' => [
            'name' => 'gpo',
            'path' => __DIR__ . '/../../logs/app.log',
        ],
    ],
);

?>

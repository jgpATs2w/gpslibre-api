<?php
function restrictToKey(){
  //TODO check valid key
  if(!isset($_GET['key']) || !\db\isConnected()){
      header('Content-type: application/json');
      die(json_encode(['success'=>false, 'message'=>'unathorized key']));
  }
}
function stringReplaceParameters($string, $parameters){
  foreach ($parameters as $key => $value) {
    $string= str_replace($key, $value, $string);
  }
  return $string;
}
function isEmptyString($string){
  return !preg_match('/\S/', $string);
}
function cliSpinnerInit(){
  $chars= ['-', '/', '\\'];
	$char=0;
	echo $chars[0];
  return 0;
}
function cliSpinnerEcho($index){
  $chars= ['-', '/', '\\'];
  	if($index==count($chars)-1) $index=-1;
  	echo chr(8).$chars[++$index];
  return $index;
}
function file_put_contents_7($file, $contents){
  $dir= dirname($file);
  if(!is_writable($dir))
    throw new \Exception("No se puede escribir en '$dir'");

  if(!is_string($contents))
    $contents= json_encode($contents);

  $r= file_put_contents($file, $contents);
  if(!is_writable($file))
    throw new \Exception("No se puede escribir en '$file'");

  //can't use chmod() function, causes Warning Operation not permited if www user <> owner
  exec("chmod 777 $file");

  return $r;
}
function httpGet($url){
  $client = new \GuzzleHttp\Client();
  $res = $client->request('GET', $url );

  $status= $res->getStatusCode();

  $body= json_decode((string)$res->getBody(), true);

  return [
    'status'=>$status,
    'body'=> $body
  ];
}
function createDir($dir){
  if(!is_dir($dir))
    mkdir($dir, 0777, true);
}

function moveUploadedFile($uploadDir){

	if( !is_dir( $uploadDir ) )
		exec("mkdir -p $uploadDir && chmod 777 $uploadDir");

	$ds= DIRECTORY_SEPARATOR;

  $tempFile = $_FILES['file'];
  $tempFileName = $tempFile['tmp_name'];
  $targetFile= rtrim($uploadDir, '/').'/'.sanitize_filename($tempFile['name']);

	move_uploaded_file($tempFileName, $targetFile);
	exec("chmod 777 $targetFile");

  if(is_file($targetFile))
    return $targetFile;
  else
    return false;

}

function prettyprint_r($array){
 foreach($array as $key => $value){
   if(is_array($value)){
     printf("<b>&nbsp;%s</b>[<br>", $key);
     prettyprint_r($value);
     echo "]<br>";
   }else if(is_object($value)){
     var_dump($value);
   }else if(is_bool($value)){
     printf("<b>%s</b>: %s<br>", $key, $value? "true": "false");
   }else
     printf("<b>%s</b>: %s<br>", $key, $value);
 }
}

function sanitize_filename( $file ){
	$file = str_replace(' ', '_', $file );
  $file = preg_replace("/[^\w\d\-_\.]*/", '', $file);
	$file = preg_replace("([\._]{2,})", '_', $file);
	$file = trim($file, " _\.");

	return $file;
}

function error_handler($errno, $errstr, $errfile, $errline) { global $trace;

    $errstr = "ERROR: " . $errno ."; $errstr on $errfile line $errline" ;

	logError( $errstr );
    switch ($errno) {
        case E_USER_WARNING:
            $trace .= $errstr;
            break;

        case E_USER_NOTICE:
        case E_USER_ERROR:
        default:
            traceAdd( $errstr );
    }
}
function fatal_handler( $exception ) {global $trace;//TODO make it work
  $errfile = "unknown file";
  $errstr  = "shutdown";
  $errno   = E_CORE_ERROR;
  $errline = 0;

  $error = error_get_last();

  if( $error !== NULL) {
    $errno   = $error["type"];
    $errfile = $error["file"];
    $errline = $error["line"];
    $errstr  = $error["message"];

    $errstr = $errno ."; $errstr on $errfile line $errline" ;
	logError( $errstr );
     printJson( 0, $errstr , $trace );
  }
}

function shutdown_handler(){

	logError( "shutdown" );

	printJson( 0, "shutdown" );
	//die();
}
//http://php.net/manual/es/function.scandir.php
function dirToArray($dir ) {

   $result = array();

   $cdir = scandir($dir);
   foreach ($cdir as $key => $value)
   {
      if (!in_array($value,array(".","..")))
      {
         if (is_dir($dir . DIRECTORY_SEPARATOR . $value))
         {
            $result[$value] = dirToArray($dir . DIRECTORY_SEPARATOR . $value);
         }
         else
         {
            $result[] = $value;
         }
      }
   }

   return $result;
}

function testPost( $POST ){
    $test= $POST['test'] or printJson( 0, 'falta test en POST', null );

    printJson(1, 'OK', $test);
}
function testGet( ){
    printJson(1, 'OK', $test);
}
function testEmptyResponse(){
	die();
}

function sh( $cmd, $log = "shell" ){

	$ret = exec( $cmd . " > /dev/null 2>&1");

	return $ret;
}

function logError( $message, $logName = "errors" ){
	\logger\error( $logName,  $message );
}

function logRemote( $message ){
	logto( "remote", $message );
}

function printSession(){
    printJson(1, 'OK', $_SESSION );
}

function toolsUpdateConfig(){ global $trace;
 $store = $_GET['store'] or printJson( 0, 'falta store en url', false );
 if( $store == '*' ){

    traceAdd( 'updating all' );
 }else{
     if( ! is_dir( "store/$store" ) )
        printJson(0,"$store not exists", null);
 }
 foreach ( glob( "store/$store" ) as $i => $v ){
     $dir = "$v/config";
     traceAdd( "updating $v..." );
     if ( is_dir( $dir ) ) {
         rmdirr( $dir );

         cpr( "tpl/solucion/config", $dir );

        traceAdd( 'ok' );
     }else
        traceAdd( "<span style=\"color:red\">$dir not exists</span>" );

 }

 printJson(1,'OK', null);
}

/** AUX **/

function flush_start(){
  if(PHP_SAPI!='cli') return;
	if (ob_get_level() == 0)
		ob_start();
}

function echo_flush( $msg, $cliOverwrite=false ){
  if(PHP_SAPI=='cli'){
    if($cliOverwrite)
      $msg= chr(8).$msg;
  	echo $msg .PHP_EOL;
  }else{
    echo $msg;
  	flush();
  	ob_flush();
  }
}

function echo_red($string, $cliOverwrite=false){
  if(PHP_SAPI=='cli')
    echo_flush("\033[41m$string\033[0m\n", $cliOverwrite);
  else
    echo_flush("<p style='color: red'>$string</p>");
}
function echo_green($string, $cliOverwrite=false){
  if(PHP_SAPI=='cli')
    echo_flush("\033[32m$string\033[0m\n", $cliOverwrite);
  else
    return "<p style='color: green'>$string</p>";
}

function flush_end(){
  if(PHP_SAPI!='cli') return;
	ob_end_flush();
}
function red($string){
  if(PHP_SAPI=='cli')
    return "\033[41m$string\033[0m\n";
  else
    echo_flush("<p style='color: red'>$string</p>");
}
function green($string){
  if(PHP_SAPI=='cli')
    return "\033[32m$string\033[0m\n";
  else
    return "<p style='color: green'>$string</p>";
}

function cpr_2($src,$dst) {
    $dir = opendir($src);
    @mkdir($dst);
    while(false !== ( $file = readdir($dir)) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
            if ( is_dir($src . '/' . $file) ) {
                cpr($src . '/' . $file,$dst . '/' . $file);
            }
            else {
                copy($src . '/' . $file,$dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}
function mkdirr( $dir ){

    if( !is_dir($dir))
        if( ! mkdir($dir, 0755, true) )
			echo 'fail to create dir ' . $dir;

}
function cpr( $source, $dest){ //timezone error in server
    if( !is_dir($dest))
        mkdir($dest, 0755, true);

    foreach (
      $iterator = new RecursiveIteratorIterator(
      new RecursiveDirectoryIterator($source, 4096),
      RecursiveIteratorIterator::SELF_FIRST) as $item) {
      	  $file = $dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName();
	      if ($item->isDir()) {
	      	if( ! is_dir( $file ) )
	        	mkdir( $file );
	      } else {
	        	copy($item, $file );
      }
    }
	return true;
}

function rmdirr( $dir , $restrictBase = true, $base = LAND ){
	//never delete files outside the BASE dir
	if( $restrictBase && strpos( $dir, $base ) === false ){
		traceAdd( "$dir not in " . BASE );
		return false;
	}

  exec("rm -r $dir > /dev/null 2>&1");
}

function traceAdd ( $msg ){ global $trace;
    $trace .= ";$msg;\n";
}

function cancel_get_file( $process ){
	return TMP . "kill_$process";
}
function cancel_check( $process ){
	$kf = cancel_get_file($process);

	if( is_file( $kf ) ){
		unlink( $kf );
		die();
	}
}

function responseEmtpy($response){
  return $response->withStatus(204)->write("null");
}

/*
* - extraParameters: [page, start, limit, trace]
*/
function response($response, $success, $data, $message=false, $extraParameters=false, $logMessage=''){
  if($logMessage!='')
    \logger\log('response', $logMessage, $success? 'info': 'error');

  $R = array();
      $R[ 'success' ] = $success? true:false;
      if($data !== false)     $R[ 'data' ] = $data;
      if($message)  $R[ 'message' ] = $message;
      if($extraParameters )
        $R= array_merge($R, $extraParameters);

  return $response->withJson($R, null, JSON_UNESCAPED_UNICODE);
}

function responseSuccess($response, $data=null, $extraParameters=false, $logMessage=''){

  return response($response, true, $data, false, $extraParameters, $logMessage);
}
function responseError($response, $message, $extraParameters=false, $logMessage=''){

  $logMessage = $logMessage==''? $message : $message.', '.$logMessage;

  return response($response, false, false, $message, $extraParameters, $logMessage);
}

?>

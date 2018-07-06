<?php

namespace logger;

function read( $tag='%', $level='%', $limit=100){
	$q= sprintf("SELECT * FROM logger WHERE tag like '%s' AND level LIKE '%s' ORDER BY id DESC LIMIT %d",$tag, $level, $limit);
	\db\query($q);

	return \db\get_array_full();
}

function log( $tag, $message='', $level="info" ){

	return Logger::log( $tag, $message, $level );
}

function error( $tag, $message='' ){

	return Logger::error( $tag, $message );
}

function info( $tag, $message='' ){

	return Logger::info( $tag, $message );
}
function debug( $tag, $message='' ){

	if( ! DEBUG )
		return;

	return Logger::debug( $tag, $message );
}

/*
*  Only use when db is not safe
*/
function log2file( $message ){
	if( !is_dir( LOGDIR ))
        mkdir ( LOGDIR );

	 if(strlen($message)>LOG_TRUNCATE)
	 	$message = substr($message, 0, LOG_TRUNCATE);

   $message = date( 'c') . " " . $message;

	 if(is_writable(FILE_APPEND))
   	file_put_contents( LOGDIR . "errors." . date( "Y-m-d" ) . ".log", "$message\n" , FILE_APPEND );
}

class Logger{

	public static function log($tag, $message, $level){

		$q= "insert into logger (logtime, tag, level, message) values (NOW(), ?, ?, ?)";

		\db\prepare($q);
		\db\execute([$tag, $level, addslashes($message)]);
	}

	public static function error( $tag, $message ){

		return self::log($tag,$message,'error');
	}

	public static function debug( $tag, $message ){

		return self::log($tag,$message,'debug');
	}
	public static function info( $tag, $message ){

		return self::log($tag,$message,'info');
	}
}

?>

<?php

use Audiocollect\Backend\Settings;
$loader = require_once 'lib/micrometa/vendor/autoload.php';
$loader->addPsr4("Audiocollect\\", __DIR__);

use Audiocollect\Backend\Log;

/*
function __autoload( $className ){
    //logToFile("looking for class: " . $className );
    require_once  __autoloadFilename($className);
}

function __autoloadFilename( $className ){
    return str_replace('_', '/', $className ) . ".php";
}
*/

function logToFile( $msg, $kat = false, $logLevel=Log::INFO){
    //syslog( LOG_DEBUG, $msg );
    if( Settings::$LOGLEVEL < $logLevel )
    	return;
    if( ! $kat ){
    	//$kat = get_calling_class();
    	$kat = 'default';
    }
    $handle = fopen (Settings::$LOGPATH, "a");
    fwrite ( $handle, date("Y-m-d H:i:s - " ) . ((isset($_SERVER) && array_key_exists('REMOTE_ADDR', $_SERVER))?$_SERVER['REMOTE_ADDR']:'console') . " [" . $kat . "] : " . $msg . "\n" );
    //fwrite ( $handle, date("Y-m-d H:i:s - " ) . " [" . $kat . "] : " . $msg . "\n" );
    fflush($handle);
    fclose($handle);
}

function get_calling_class() {

	//get the trace
	$trace = debug_backtrace();
	$handle = fopen ("/var/log/php/punkt-k.debug.log", "a");
	fwrite ( $handle, date("Y-m-d H:i:s - " ) . $_SERVER['REMOTE_ADDR'] . 
		" [getCallingClass] : " . print_r($trace[1], true) . "\n" );
	fflush($handle);
	fclose($handle);
	
	// Get the class that is asking for who awoke it
	$class = $trace[1]['file'];

	// +1 to i cos we have to account for calling this function
	for ( $i=1; $i<count( $trace ); $i++ ) {
		if ( isset( $trace[$i] ) ) // is it set?
			if ( $class != $trace[$i]['class'] ) // is it a different class
			return $trace[$i]['class'];
	}
}

function strrpos($haystack, $needle) {
    $index = strpos(strrev($haystack), strrev($needle));
    if($index === false) {
        return false;
    }
    $index = strlen($haystack) - strlen($needle) - $index;
    return $index;
}

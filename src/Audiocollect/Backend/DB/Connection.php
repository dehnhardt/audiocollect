<?php
/*
 * Created on 19.12.2007
 * Singleton for connection
 */
namespace Audiocollect\Backend\DB;

use Audiocollect\Backend\Settings;
use Audiocollect\Backend\Log;

class Connection{
	//singleton
 	private static $instance = null;
 	private $connectparams = null;

 	private $connection = null;

 	/**
 	 * retrieves the single instance of the connection
 	 */

	public static function getInstance(){
		if( !isset(self::$instance) ){
			self::$instance = new Connection();
		}
		return self::$instance;
	}

 	/**
 	 * private constructor to avoid creation outside this class
 	 */

 	private function __construct(){
		$this->connectparams = Settings::$CONNECTPARAMS;
 		//$this->getConnection();
 	}

 	private function getConnection(){
		//logToFile(  "In Function: " . $this->connection );
 		//if( !defined("$this->connection") ){ logToFile("notdefined");}
 		if( !isset($this->connection)  ){logToFile("connection not set");}
 		if ($this->connection == 0) {logToFile("zahl 0");}

 		if( !isset($this->connection) || ($this->connection == 0) ){
 			//logToFile( "creating connection");
 			$this->connection = pg_connect($this->connectparams )
			or die("Connection failed" );
			//logToFile( "Connection created $this->connection");
 			/*$result = pg_set_option( $this->connection, "verbose_errors", True);
			logToFile( "Verbose set");*/
 		}
 		else{
			//logToFile( "Already connected: Connection");
 		}
		//logToFile("Returning connection");
		return $this->connection;
 	}

 	public function quote( $str ){
 	    return pg_escape_string( $this->getConnection(), $str );
 	}
 	
 	public function execute( $sql ){
 		$conn = $this->getConnection();
 		logToFile( $sql . ", Connection: $conn", Log::DEBUG );
 		return pg_query( $conn, $sql );
 	}

 	public function getResultSet( $sql ){
 		$conn = $this->getConnection();
 		logToFile( $sql . ", Connection: $conn", Log::INFO );

 		$res = pg_query($conn, $sql );
 		if( !isset($res) ){
 			logToFile( "Abfrage nicht erfolgreich" );
 			return false;
 		}
 		else if( ! $res  ){
		    $error_msg = pg_error( $res );
 			logToFile( "Abfrage nicht erfolgreich:  " . $error_msg );
 			return false;
 		}
 		else{
 			logToFile( "Abfrage erfolgreich");
 			return new ResultSet($res);
 		}
 	}

 	public function getResultLine( $sql ){
 		logToFile( $sql, 'Connection', Log::DEBUG );
 		$q = $this->getResultSet($sql);
 		if( $res = $q->fetchArray() ){
	 		logToFile( "fetch done");
 			$q->free();
 			return $res;
 		}
 		else{
 			return false;
 		}
 	}

 	public function commit( ){
 		pg_commit($this->getConnection());
 	}

 	public function close() {
 		pg_disconnect($this->getConnection());
		$this->connection=0;
 	}
}

class ResultSet{

	private $resultSet = null;

	public function bindAllVOs( $voClass, $key = null ){
		$voCollection = array();

		while( $zeile = $this->fetchArray()){
			$vo = $this->bindVO( $voClass, $zeile );
			if( $key != null ){
				$voCollection[$zeile[$key]] = $vo;
			}
			else{
				$voCollection[] = $vo;
			}
		}
		return $voCollection;
 	}

	public function bindSingleVO( $voClass ){
		$zeile = $this->fetchArray();
		return $this->bindVO( $voClass, $zeile );
 	}

	private function property_exists( $object, $pname) {
	    $reflect = new ReflectionObject($object);
    	try{
	        $reflect->getProperty( $pname );
    	}catch( ReflectionException $e){
        	return false;
    	}
	    return true;
	}

	private function bindVO( $voClass, $zeile ){
		$vo = new $voClass();
		foreach ( $zeile as $name=>$value ) {
			if( method_exists( $vo, "set" . ucfirst($name))){
				$method = "set" . ucfirst($name);
				$vo->$method($value);
			} else if( $this->property_exists( $vo, $name ) ){
			    $vo->$name = trim($value);
			}
		}
		if( method_exists($vo, "auto_evaluate" )){
			$vo->auto_evaluate();
		}
		return $vo;
	}

	public function __construct( $resultSet ){
		$this->resultSet = $resultSet;
	}

	public function rowCount(){
		$num_rows = pg_num_rows( $this->resultSet );
		if( $num_rows < 0 ) {
			$num_rows = abs( $num_rows );    # take the absolute value as an estimate
		}
		logToFile( "Anzahl Zeilen: $num_rows");
		return $num_rows;
	}

	public function toArray(){
		if( $this->resultSet ){
			#logToFile("Columns = " . $this->colCount() );
			$alle = array();
			while( $einzel = $this->fetchArray()){
				$alle[]=$einzel;
			}
			return $alle;
		}
	}

	public function colCount(){
		return pg_num_fields( $this->resultSet );
	}

 	public function fetchArray( ){
 		return pg_fetch_array( $this->resultSet );
 	}
 	
 	public function fetchObject( ){
 	    return pg_fetch_object( $this->resultSet );
 	}
 	
 	public function fetchRow( ){
 		return pg_fetch_row( $this->resultSet );
 	}

 	public function free(){
 		pg_free_result($this->resultSet);
 	}

 	public function getFields(){
 		$fields = array();
		while( ($field = pg_fetch_object( $this->resultSet )) ) {
		    //logToFile(print_r($field, true));
			$fields[] = $field->name;
		 }
		 return $fields;
 	}

}
?>

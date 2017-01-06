<?php
/*
 *
 *	Created on: 30.11.2009
 *	Created by: dehnhardt
 *
 */

require_once 'db/Connection.php';

class db_DBProcessor{
	protected $connection = null;

	public function __construct(){
		$this->connection = Connection::getInstance();
	}
	
	public function createWhereFromArray( $clauses ){
		$sql = '';
		logToFile(print_r($clauses, true), "DBProcessor", Log::INFO);
		foreach( $clauses as $clause ){
			if( $sql == '') $sql = " where ";
			else $sql .= " and ";
			$sql .= ' ' . $clause ."\n";
		}
		return $sql;
	}

}
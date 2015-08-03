<?php

class database{
	
	private $host;
	private $user;
	private $password;
	private $database;
	public function __construct($host,$user,$password,$database){
		//require_once 'db_config.php';
		$this->host = $host;
		$this->user = $user;
		$this->password = $password;
		$this->database = $database;
	}
	
	public function open_database_connection(){
		
		return new mysqli($this->host, $this->user, $this->password, $this->database);
	}
	
	public function __destruct(){
		
	}
	
	
}

?>
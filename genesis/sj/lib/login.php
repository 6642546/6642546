<?php

class MarsConnect {
	// just creates connection object
	private $host;
	private $user;
	private $password;
	private $db;

	public function __construct(){
		$this->host = "cat03";
		$this->user = "marsapp";
		$this->password = "talos4";
		$this->db = "mars";
	}
	public function connect() {
		$con = new mysqli($this->host, $this->user, $this->password, $this->db) or die('Could not connect to server');
		return $con;
	}

}
?>
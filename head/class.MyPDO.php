<?php
class MyPDO extends PDO {
	private $is_server = 0;
	private $engine;
	private $host;
	private $database;
	private $user;
	private $pass;
	public function __construct() {
		if ($this->is_server) {
			$this->engine = 'mysql';
			$this->host = 'hdm109008257.my3w.com';
			$this->database = 'hdm109008257_db';
			$this->user = 'hdm109008257';
			$this->pass = 'sql6285720';
		} else {
			$this->engine = 'mysql';
			$this->host = 'localhost';
			$this->database = 'x';
			$this->user = 'root';
			$this->pass = '6285720';
		}
		
		$dns = $this->engine . ':dbname=' . $this->database . ";host=" . $this->host;
		parent::__construct ( $dns, $this->user, $this->pass );
	}
}
?> 
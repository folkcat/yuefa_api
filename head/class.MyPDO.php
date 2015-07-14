<?php 
class MyPDO extends PDO { 
	private $is_server=1;
     
     private $engine; 
     private $host; 
     private $database; 
     private $user; 
     private $pass; 
     
     public function __construct(){ 
     	if($this->is_server){
     		
     	}else{
     		$this->engine = 'mysql'; 
         	$this->host = 'localhost'; 
         	$this->database = 'x'; 
         	$this->user = 'root'; 
         	$this->pass = '6285720'; 
     	}
         
         $dns = $this->engine.':dbname='.$this->database.";host=".$this->host; 
         parent::__construct( $dns, $this->user, $this->pass ); 
     } 
 } 
?> 
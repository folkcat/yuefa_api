<?php
include_once './head/class.MyPDO.php';

class ShopSession{
	
	//
	private $user_session_id;//用户传来的session id
	private $pdo_handler;
	private $origin_session_id;
	private $email;
	
	//针对还没创建session id 的情况
	public function __construct($para,$flag){
		if($flag==1){//Email的情况
			$this->pdo_handler=new MyPDO();
			//Set up the handler
			session_set_save_handler(
					array(&$this,'_session_open_method'),
					array(&$this,'_session_close_method'),
					array(&$this,'_session_read_method'),
					array(&$this,'_session_write_method'),
					array(&$this,'_session_destroy_method'),
					array(&$this,'_session_gc_method')
					);
			session_start();
			$this->origin_session_id=session_id();
			$this->create_session($para, $this->origin_session_id);
			$this->email=$para;
			
		}
		if($flag==2){//Session id 的情况
			$this->user_session_id=$para;
			
		}
		
	}
	
	public function get_email() {
		$str_query = "select email from shop_session where session_id=:id limit 1";
		$db = new MyPDO ();
		$result = $db->prepare ( $str_query );
		$result->execute ( array (
				'id' => $this->user_session_id
		) );
		$row = $result->fetch ();
		$email = $row ['email'];
		return $email;
	}
	public function get_session_id(){
		return $this->origin_session_id;
	}
	//
	private function create_session($email, $session_id) {
		$str_query = "insert into shop_session(email,session_id)values(:email,:id)";
		$result = $this->pdo_handler->prepare ( $str_query );
		$result->execute ( array (
				':email' => $email,
				':id' => $session_id 
		) );
	}
	
	
	
	
	
	private function _session_open_method() {
		// Do nothing
		return (true);
	}
	private function _session_close_method() {
		$this->pdo_handler=null;
		return (true);
	}
	private function _session_read_method() {
		// Do nothing
		return (true);
	}
	private function _session_write_method() {
		// Do nothing
		return (true);
	}
	private function _session_destroy_method() {
		// Do nothing
		return (true);
	}
	private function _session_gc_method() {
		// Do nothing
		return (true);
	}
	
	
}

// $haha=new MySession("donghaifeng@hotmail.com",1);
// echo $haha->get_ssession_id();
// $he=new MySession("evmuv1r84vs6avupp231ffhfs6", 2);
// echo $he->get_email();
?>
<?php
include_once '../head/class.MyPDO.php';
class UploadUtil{
	public static function insert_image($file_name,$owner){
		$str_query="insert into image(owner,file_name)values('$owner','$file_name')";
		$db=new MyPDO();
		$num=$db->exec($str_query);
		return $num;
	}
	public static function get_session(){
		session_set_save_handler(
				array(&$this,'_session_open_method'),
				array(&$this,'_session_close_method'),
				array(&$this,'_session_read_method'),
				array(&$this,'_session_write_method'),
				array(&$this,'_session_destroy_method'),
				array(&$this,'_session_gc_method')
		);
		session_start();
		return session_id();
	}
	
}
echo UploadUtil::get_session();
//echo UploadUtil::insert_image("this is file name","dognhaifeng@hotmail.com");


?>
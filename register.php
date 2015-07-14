<?php
/*
 * 功能：注册用户
 * post变量 $_POST['email']   $_POST['password']
 * 
 * 
 * 若正确返回    		1 	 session_id
 * 邮箱格式错误 		-1 		0
 * 若用户已经存在  	-2   	0
 * 致命错误			 0 		0
 */
include_once './head/class.MyPDO.php';
include_once './libs/class.UserUtil.php';
include_once './libs/class.RegUtil.php';
include_once './libs/class.SessionUtil.php';
include_once './libs/MySession.php';

error_reporting(0);


$email=$_GET['email'];
$password=$_GET['password'];
if(!$email|!$password){
	echo "{\"result\":\"0\",\"session_id\":\"0\"}";//没有输入用户名或密码参数
	exit;
}

// echo $email;
// echo $password;

if(RegUtil::is_email($email)){//符合Email正则表达式
	if(UserUtil::create_user($email, $password)){//正确
		$success_str="{\"result\":\"1\",\"session_id\":\"";//正确
		$session=new MySession($email, 1);
		$session_id=$session->get_session_id();
		$success_str=$success_str.$session_id."\"}";
		echo $success_str;
	}else{
		echo "{\"result\":\"-2\",\"session_id\":\"0\"}";//用户已经存在
	}
}else{
	echo "{\"result\":\"-1\",\"session_id\":\"0\"}";//不符合Email正则表达式
}




//=========================================================================================================





?>
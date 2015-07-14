<?php

/*
 * 功能：登陆
 * POST变量，email password
 * 打印值：完全正确1  并且打印session-id
 * 			
 *		不存在用户名 0
 * 		存在用户但密码错误 1
 * 
 */


include_once './libs/class.SessionUtil.php';
include_once './libs/class.RegUtil.php';
include_once './libs/class.UserUtil.php';
include_once './libs/MySession.php';
error_reporting(0);

$str_state=" ";

$email=$_GET['email'];
$password=$_GET['password'];

if(!$email|!$password){
	echo gen_result(0);//致命错误
	exit;
}

$v_flag=0;
if(RegUtil::is_email($email)){//邮箱格式正确
	$v_flag=UserUtil::is_user($email, $password);
	switch($v_flag){
		case 1://用户名和密码匹配
			$session=new MySession($email, 1);
			$session_id=$session->get_session_id();
			$nick_name=UserUtil::get_nick_name($email);
			echo gen_result(1, $session_id,$nick_name);
			break;
			
		case -2://不存在该用户
			echo gen_result(-2);
			break;
		case -3://存在但密码错误
			echo gen_result(-3);
			break;
	}
}else{
	echo gen_result(-1);//邮箱格式错误
}

function gen_result($result,$session_id=0,$nick_name="Not Get Yet"){
	return "{\"result\":\""
			.$result
			."\",\"session_id\":\""
			.$session_id
			."\",\"nick_name\":\""
			.$nick_name
			.		"\"}";
	
}


?>
<?php

/*
 * 功能：登陆
 * POST变量，email password
 * 打印值：不存在用户名 0
 * 			存在用户但密码错误 1
 * 			完全正确 2 并且打印session-id
 */




include_once './libs/class.SessionUtil.php';
include_once './libs/class.RegUtil.php';
include_once './libs/class.UserUtil.php';

$str_state=" ";

$email=$_GET['email'];
$password=$_GET['password'];



$v_flag=0;
if(RegUtil::is_email($email)){
	$v_flag=UserUtil::is_user($email, $password);
	switch($v_flag){
		case 1:
			$str_state=gen_result(1, 0);
			break;
		case 2:
			$session_id=SessionUtil::create_session($email);
			//echo $session_id;
			$nick_name=UserUtil::get_nick_name($email);
			$str_state=gen_result(2, $session_id,$nick_name);
			break;
		default:
			$str_state=gen_result(0, 0);
			break;
	}
	echo $str_state;
	
		
}

function gen_result($result,$session_id,$nick_name="NULLLLL"){
	return "{\"result\":\""
			.$result
			."\",\"session_id\":\""
			.$session_id
			."\",\"nick_name\":\""
			.$nick_name
			.		"\"}";
	
}


?>
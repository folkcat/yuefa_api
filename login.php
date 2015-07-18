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
include_once './head/Config.php';
include_once './libs/class.RegUtil.php';
include_once './libs/MySession.php';
error_reporting(0);

$email=$_GET['email'];
$password=$_GET['password'];

if(!$email|!$password){
	echo gen_result(0);//参数错误
	exit;
}

$db = new MyPDO ();
$nick_name;
$icon;

$v_flag=0;
if(RegUtil::is_email($email)){//邮箱格式正确
	$v_flag=is_user($email, $password);
	switch($v_flag){
		case 1://用户名和密码匹配
			$session=new MySession($email, 1);
			$session_id=$session->get_session_id();
			//$icon=$prefix_url
			echo gen_result(1, $session_id,$nick_name,$icon);
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

function gen_result($result,$session_id=0,$nick_name="undefine",$icon="undefine"){
	return "{\"result\":\""
			.$result
			."\",\"session_id\":\""
			.$session_id
			."\",\"icon\":\""
			.$icon
			."\",\"nick_name\":\""
			.$nick_name
			.		"\"}";
	
}
function is_user($_email, $password) {//UserUtil::is_user
	global $url_prefix;
	$str_query = "select email,password,display_name,icon from user_info where email=:email limit 1";
	global $db;
	global $nick_name;
	global $icon;
	$result = $db->prepare ( $str_query );
	$result->execute ( array (
			':email' => $_email
	) );
	$row = $result->fetch ();
	$email = $row ['email'];
	if (! $email) {
		return -2;//不存在该用户
	} else {
		if ($row ["password"] == $password) {
			$nick_name=$row['display_name'];
			$icon=$url_prefix."user_icon/".$row['icon'];
			return 1;//正确
		} else {
			return -3;//存在，但密码错误
		}
	}

}


?>
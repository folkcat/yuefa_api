<?php
/*
 * 功能：注册一家商店
 */

include_once './libs/class.RegUtil.php';
include_once './head/class.MyPDO.php';
include_once './libs/ShopSession.php';

//error_reporting(0);
$email=$_GET['email'];
$password=$_GET["password"];
$title=$_GET['title'];
$longi=$_GET['longi'];
$lagi=$_GET['lagi'];


if(!($email&$password&$title&$longi&$lagi)){
	echo "{\"result\":\"0\",\"session_id\":\"0\"}";//参数错误
	exit;
}

if(RegUtil::is_email($email)){//符合Email正则表达式
	if(create_shop($email, $password,$title,$longi,$lagi)){//正确
		$success_str="{\"result\":\"1\",\"session_id\":\"";//正确
		$session=new ShopSession($email, 1);
		$session_id=$session->get_session_id();
		$success_str=$success_str.$session_id."\"}";
		echo $success_str;
	}else{
		echo "{\"result\":\"-2\",\"session_id\":\"0\"}";//用户已经存在
	}
}else{
	echo "{\"result\":\"-1\",\"session_id\":\"0\"}";//不符合Email正则表达式
}


function create_shop($email, $password,$title,$longi,$lagi) {
	$db = new MyPDO ();
	$str_query = "insert into shop_info (email,password,title,longi,lagi) values(:email,:password,:title,:longi,:lagi)";
	$result = $db->prepare ( $str_query );
	$num = $result->execute ( array (
			':email' => $email,
			':password' => $password,
			':title'=>$title,
			'longi'=>$longi,
			'lagi'=>$lagi
	) );
	return $num;
}


?>
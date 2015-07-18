<?php
include_once './head/Config.php';
include_once './libs/MySession.php';
// error_reporting(0);

$session_id = $_GET ['session_id'];

if (! $session_id) {
	echo "{\"result\":\"0\"}"; // 参数错误
	exit ();
}
$session = new MySession ( $session_id, 2 );

$email = $session->get_email ();
if (! $email) {
	echo "{\"result\":\"-1\"}"; // session_id 无效
}

get_user_info ( $email );
function get_user_info($email) {
	global $url_prefix;
	$str_query = "select display_name,icon from user_info where email=:email limit 1";
	$db = new MyPDO ();
	$result = $db->prepare ( $str_query );
	$result->execute ( array (
			':email' => $email 
	) );
	$row = $result->fetch ();
	echo "{\"result\":\"1\",\"email\":\"" . $email . "\",\"nick_name\":\"" . $row['display_name']."\",\"icon\":\"".$url_prefix."user_icon/".$row['icon']."\"}";
	return;
}

?>
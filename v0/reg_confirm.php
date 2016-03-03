<?php
/*
 * 功能：确认注册
 * post变量 $_POST['key']
 *
 *
 * 若正确返回 1 session_id
 * 邮箱格式错误 -1 0
 * 若用户已经存在 -2 0
 * 致命错误 0 0
 */
include_once 'head/class.MyPDO.php';
$db = new MyPDO ();

error_reporting(0);

$key = $_GET ['key'];
// echo $key;
echo to_form ( $key );
function to_form($key) {
	global $db;
	
	$str_query = "select email,status_ from user_reg_candida where random_key=:key  limit 1";
	$result = $db->prepare ( $str_query );
	$result->execute ( array (
			':key' => $key 
	) );
	$row = $result->fetch ();
	$email = $row ['email'];
	$candida_status=$row['status_'];
	if ($candida_status==1) {
		$str_query = "update user_info set status_=1 where email=:email";
		$result = $db->prepare ( $str_query );
		$num = $result->execute ( array (
				':email' => $email
		) );
		if($num){
			$str_query = "update user_reg_candida set status_='2' where random_key=:key";
			$result = $db->prepare ( $str_query );
			$num = $result->execute ( array (
					':key' => $key
			) );
			echo "认证成功成功，快去登录吧。";
		}else{
			echo "出错了";
		}
	}else if($candida_status==2){
		echo "该用户已经认证。";	
	}else{
		echo "已过期";
	}
	
	
}

?>
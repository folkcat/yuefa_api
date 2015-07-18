<?php
include_once './head/class.MyPDO.php';

$db = new MyPDO ();

//insert_image("aaaaaa.jpg", "donghaifeng@hotmail.com");


echo verify("gmn9bf1btiac25lvf6vumdd7q6");















function insert_image($file_name,$email) {
	global $db;
	$str_query = "update user_info set icon=:icon where email=:email ";
	$result=$db->prepare($str_query);
	$num = $result->execute(array('icon'=>$file_name,'email'=>$email));
	echo  $num;
}

function verify($session_id) {//由于性能，从UserUtil复制而来
	$str_query = "select email from session where session_id=:id limit 1";
	global $db;
	$result = $db->prepare ( $str_query );
	$result->execute ( array (
			':id' => $session_id
	) );
	$row = $result->fetch ();
	$exist = $row ['email'];
	if ($exist) {
		return $exist;
	} else {
		return 0;
	}
}


?>
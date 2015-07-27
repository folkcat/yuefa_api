<?php
include_once './head/class.MyPDO.php';
include_once './libs/class.RegUtil.php';


$db = new MyPDO ();





function en_candidate($email, $password) {
	global  $db;
	$key=RegUtil::randomkeys(32);
	$str_query = "insert into user_reg_candida (email,password,random_key) values(:email,:password,:key)";
	$result = $db->prepare ( $str_query );
	$num = $result->execute ( array (
			':email' => $email,
			':password' => $password,
			':key'=>$key
	) );
	return $num;
}

if(en_candidate("1234@51234.com", "123456456"))
	echo "Success";
else 
	echo "Fail";






?>
<?php


include_once 'head/Config.php';
include_once 'libs/MySession.php';
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
	exit();
}


$api_key="wmeoP8zXgRtfrfxu_9XZLC5GMB7j3L91vZ2VxIzR";
$api_secret="8GxousvoXBwHgTCykJx2VYRC-NJUYuskgp-oRLSt";
$buket_name="yuefa";

require 'vendor/autoload.php';
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

// 设置信息
$APP_ACCESS_KEY = $api_key;
$APP_SECRET_KEY = $api_secret;
$bucket = $buket_name;
$auth = new Auth($APP_ACCESS_KEY, $APP_SECRET_KEY);
$token = $auth->uploadToken($bucket);
$result_arr=array("result"=>1,"token"=>$token);
echo json_encode($result_arr);
?>
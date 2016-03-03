<?php
/*
 * 功能：获取用户概要信息
 * $_GET参数：session_id
 * 
 * 				result
 * 正确：			1
 * 参数错误			0
 * session_id失效	-1
 * 
 * 
 */
include_once 'head/class.MyPDO.php';
include_once 'head/Config.php';

/*功能：上传用户头像图片
 * 
 * post、get值：$session_id,文件
 * 
 * 					
 * 返回结果：				result
 * 		成功						1
 * 		参数错误					0
 * 	session_id无效（失效）		-1
 * 		其它错误					-2
 * 
 * 
 * 
 */
error_reporting(0);
$erro_str = "{\"result\":\"0\"}";  //参数错误
$fail_str = "{\"result\":\"-1\"}"; // session_id 失效
$unknow_erro_str = "{\"result\":\"-2\"}"; // 其它错误

$session_id=$_GET['session_id'];
if(!$session_id){
	echo $erro_str;
	exit;
}
$db = new MyPDO ();
$email=verify($session_id);
if(!$email){
	echo $fail_str;
	exit;
}



if (! verify ( $_GET ['session_id'] )) {
	echo $erro_str;
	exit ();
}



header ( 'Content-Type: text/plain; charset=utf-8' );

try {
	
	// Undefined | Multiple Files | $_FILES Corruption Attack
	// If this request falls under any of them, treat it invalid.
	if (! isset ( $_FILES ['img_upload'] ['error'] ) || is_array ( $_FILES ['img_upload'] ['error'] )) {
		throw new RuntimeException ( 'Invalid parameters.' );
	}
	
	// Check $_FILES['upfile']['error'] value.
	switch ($_FILES ['img_upload'] ['error']) {
		case UPLOAD_ERR_OK :
			break;
		case UPLOAD_ERR_NO_FILE :
			throw new RuntimeException ( 'No file sent.' );
		case UPLOAD_ERR_INI_SIZE :
		case UPLOAD_ERR_FORM_SIZE :
			throw new RuntimeException ( 'Exceeded filesize limit.' );
		default :
			throw new RuntimeException ( 'Unknown errors.' );
	}
	
	// You should also check filesize here.
	if ($_FILES ['img_upload'] ['size'] > 1000000) {
		throw new RuntimeException ( 'Exceeded filesize limit.' );
	}
	
	// DO NOT TRUST $_FILES['upfile']['mime'] VALUE !!
	// Check MIME Type by yourself.
	$finfo = new finfo ( FILEINFO_MIME_TYPE );
	if (false === $ext = array_search ( $finfo->file ( $_FILES ['img_upload'] ['tmp_name'] ), array (
			'jpg' => 'image/jpeg',
			'png' => 'image/png',
			'gif' => 'image/gif' 
	), true )) {
		throw new RuntimeException ( 'Invalid file format.' );
	}
	
	// You should name it uniquely.
	// DO NOT USE $_FILES['upfile']['name'] WITHOUT ANY VALIDATION !!
	// On this example, obtain safe unique name from its binary data.
	$store_name = sha1_file ( $_FILES ['img_upload'] ['tmp_name'] );
	if (! move_uploaded_file ( $_FILES ['img_upload'] ['tmp_name'], sprintf ( './user_icon/%s.%s', $store_name, $ext ) )) {
		throw new RuntimeException ( 'Failed to move uploaded file.' );
	}
	
	// echo 'File is uploaded successfully.';
	$file_name = $store_name . "." . $ext;
	// echo $file_name;
	if(insert_image($file_name, $email)){
		$url=$url_prefix."user_icon/".$file_name;
		echo "{\"result\":\"1\",\"url\":\"".$url."\"}";
	}else{
		echo $unknow_erro_str;
	}
} catch ( RuntimeException $e ) {
	echo $fail_str;
	// echo $e->getMessage();
}

function insert_image($file_name,$email) {
	global $db;
	$str_query = "update user_info set icon=:icon where email=:email ";
	$result=$db->prepare($str_query);
	$num = $result->execute(array('icon'=>$file_name,'email'=>$email));
	return  $num;
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
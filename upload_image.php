<?php
/*
 * 功能：上传图片文件
 * POST变量：session_id  文件
 * 
 *            result
 * 成功                 1
 * 失败                 0
 * session无效   -1
 */

include_once './head/class.MyPDO.php';
include_once './libs/class.UserUtil.php';

$success_str="{\"result\":\"1\"}";
$fail_str="{\"result\":\"0\"}";
$erro_str="{\"result\":\"-1\"}";//session_id  失效

if(!UserUtil::verify($_GET['session_id'])){
	echo $erro_str;
	exit;
}

$db=new MyPDO();

header('Content-Type: text/plain; charset=utf-8');

try {
    
    // Undefined | Multiple Files | $_FILES Corruption Attack
    // If this request falls under any of them, treat it invalid.
    if (
        !isset($_FILES['img_upload']['error']) ||
        is_array($_FILES['img_upload']['error'])
    ) {
        throw new RuntimeException('Invalid parameters.');
    }

    // Check $_FILES['upfile']['error'] value.
    switch ($_FILES['img_upload']['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            throw new RuntimeException('No file sent.');
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            throw new RuntimeException('Exceeded filesize limit.');
        default:
            throw new RuntimeException('Unknown errors.');
    }

    // You should also check filesize here. 
    if ($_FILES['img_upload']['size'] > 1000000) {
        throw new RuntimeException('Exceeded filesize limit.');
    }

    // DO NOT TRUST $_FILES['upfile']['mime'] VALUE !!
    // Check MIME Type by yourself.
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    if (false === $ext = array_search(
        $finfo->file($_FILES['img_upload']['tmp_name']),
        array(
            'jpg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
        ),
        true
    )) {
        throw new RuntimeException('Invalid file format.');
    }

    // You should name it uniquely.
    // DO NOT USE $_FILES['upfile']['name'] WITHOUT ANY VALIDATION !!
    // On this example, obtain safe unique name from its binary data.
    $store_name=sha1_file($_FILES['img_upload']['tmp_name']);
    if (!move_uploaded_file(
        $_FILES['img_upload']['tmp_name'],
        sprintf('./uploads/%s.%s',
            $store_name,
            $ext
        )
    )) {
        throw new RuntimeException('Failed to move uploaded file.');
    }

   // echo 'File is uploaded successfully.';
    $file_name= $store_name.".".$ext;
    //echo $file_name;
    if(file_exist($file_name)){
    	echo "file has been exist";
    	echo  $success_str;
    }else{
    	echo "file not exist";
    	insert_image($file_name);
    	echo $success_str;
    }
    
    
    
    

} catch (RuntimeException $e) {
	echo $fail_str;
	// echo $e->getMessage();

}
function file_exist($file_name){
	global $db;
	$str_query="select file_name from image where file_name=:file_name limit 1";
	$result = $db->prepare ( $str_query );
	$result->execute ( array (
			':file_name' => $file_name
	) );
	$row = $result->fetch ();
	$name = $row ['file_name'];
	if($name){
		return 1;
	}else{
		return 0;
	}
	
}
function insert_image($file_name){
	global $db;
	$str_query="insert into image set file_name='$file_name' ";
	$num=$db->exec($str_query);
	return $num;
}

?>
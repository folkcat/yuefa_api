<?php 

header("content-type:text/html;charset=utf-8");
date_default_timezone_set("PRC");
//$pImg=$_FILES['pImg'];
$pImg=$_FILES[pImg];
var_dump($_FILES);

//print_r($pImg);
if($pImg['error']==UPLOAD_ERR_OK){
	//取得扩展名
	$extName=strtolower(end(explode('.',$pImg['name'])));
	//echo $extName;
	$filename=date("file").".".$extName;
	//echo $filename;
	$dest="uploads/".$filename;
	move_uploaded_file($pImg['tmp_name'],$dest);
	echo "上传成功";
}else{
	echo "上传错误";
}


?>
<?php

include_once './head/class.MyPDO.php';

$success_str="{\"result\":\"1\"}";
$fail_str="{\"result\":\"0\"}";


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
    
    insert_image($file_name);
    echo $success_str;
    
    

} catch (RuntimeException $e) {
	echo $fail_str;

   // echo $e->getMessage();

}


function insert_image($file_name){
	$str_query="insert into image set file_name='$file_name' ";
	$db=new MyPDO();
	$num=$db->exec($str_query);
	return $num;

}

?>
<?php
$res= getView();
echo $res;
login();

function curl_request($url){
	return file_get_contents($url);
}
function getView(){
	$res;
	$url = 'http://210.34.213.87/default2.aspx';
	$result = curl_request($url);
	//echo $result;
	$pattern = '/<input type="hidden" name="__VIEWSTATE" value="(.*?)" \/>/is';
	preg_match_all($pattern, $result, $matches);
	$res = $matches[1][0];
	
	return $res;
}


function login(){
	global $res;
	$url = 'http://210.34.213.87/default2.aspx';
	$post['__VIEWSTATE'] = $res;
	$post['txtUserName'] = "1307012207";
	$post['TextBox2'] = "asd6285720";
	$post['RadioButtonList1'] =iconv('utf-8', 'gb2312', '学生');
	//$post['Button1'] = iconv('utf-8', 'gb2312', '登录');
	$result =curl_request($url,$post,'', 1);
	print_r($result);
	return $result;
}



?>
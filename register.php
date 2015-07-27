<?php
/*
 * 功能：注册用户
 * post变量 $_POST['email']   $_POST['password']
 * 
 * 
 * 若正确返回    		 1
 * 邮箱格式错误 		-1
 * 若用户已经存在  	-2
 * 若用户已经入候选   -3 
 * 致命错误			 0 
 * 插入数据库成功但发送邮件失败 -4
 */
include_once './head/class.MyPDO.php';
include_once './libs/class.UserUtil.php';
include_once './libs/class.RegUtil.php';
include_once './libs/MySession.php';
include_once './smtp.php';

error_reporting(0);
$db = new MyPDO ();

$email=$_GET['email'];
$password=$_GET['password'];
if(!$email|!$password){
	echo "{\"result\":\"0\",\"session_id\":\"0\"}";//参数错误
	exit;
}

// echo $email;
// echo $password;

if(RegUtil::is_email($email)){//符合Email正则表达式
	if(!exist_user($email)){//用户不存在正确
		$key=RegUtil::randomkeys(32);
		if(en_candidate($email, $password,$key)){
			if(send_identify_email($email, $key)){
				echo "{\"result\":\"1\"}";//正确
			}else{
				echo "{\"result\":\"-4\"}";//用户未激活
			}
		}else{
			echo "{\"result\":\"-3\"}";//用户未激活
		}
	}else{
		echo "{\"result\":\"-2\"}";//用户已经存在
	}
}else{
	echo "{\"result\":\"-1\"}";//不符合Email正则表达式
}

function en_candidate($email, $password,$key) {
	global  $db;
	$str_query = "insert into user_reg_candida (email,password,random_key) values(:email,:password,:key)";
	$result = $db->prepare ( $str_query );
	$num = $result->execute ( array (
			':email' => $email,
			':password' => $password,
			':key'=>$key
	) );
	
	return $num;
}


/*
 * 功能：确定用户是否存在
 * 参数：用户邮箱
 * 返回值：存在，true；不存在 false
 */
function exist_user($_email) {
	$str_query = "select email from user_info where email=:email limit 1";
	global $db;
	global $nick_name;
	global $icon;
	$result = $db->prepare ( $str_query );
	$result->execute ( array (
			':email' => $_email
	) );
	$row = $result->fetch ();
	$email = $row ['email'];
	if($email){
		return true;
	}else{
		return false;
	}
}

/*
 * 功能：发送认证邮箱
 * 参数：邮箱、key
 * 返回值 
 */
function send_identify_email($email,$key) {
	$url="http://yuefa.me/reg_confirm.php?key=".$key;
	
	// 使用163邮箱服务器
	$smtpserver = "smtp.163.com";
	// 163邮箱服务器端口
	$smtpserverport = 25;
	// 你的163服务器邮箱账号
	$smtpusermail = "yuefame@163.com";
	// 收件人邮箱
	$smtpemailto = $email;
	// 你的邮箱账号(去掉@163.com)
	$smtpuser = "yuefame"; // SMTP服务器的用户帐号
	// 你的邮箱密码
	$smtppass = "khirljfylajsboee"; // SMTP服务器的用户密码
	 
	// 邮件主题
	$mailsubject = "激活账号";
	// 邮件内容
	$mailbody = "欢迎注册约发么，请点击下方URL激活您的账号：<br/>".$url;
	// 邮件格式（HTML/TXT）,TXT为文本邮件
	$mailtype = "HTML";
	// 这里面的一个true是表示使用身份验证,否则不使用身份验证.
	$smtp = new smtp ( $smtpserver, $smtpserverport, true, $smtpuser, $smtppass );
	// 是否显示发送的调试信息
	$smtp->debug = false;
	// 发送邮件
	if($smtp->sendmail ( $smtpemailto, $smtpusermail, $mailsubject, $mailbody, $mailtype ))
		return true;
	else 
		return false;
}

//=========================================================================================================





?>
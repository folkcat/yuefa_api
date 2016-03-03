<?php
require ("smtp.php");
error_reporting ( 0 );
function send_identify_email($email,$key) {
	$url="http://yuefa.me/yuefa_api/reg_confirm.php?key=".$key;

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
send_identify_email ( "673378507@qq.com", "fasdfasdf465" );

?>
<?php
/*
 * 正则表达式工具
 */
class RegUtil{
	
	/*
	 * 匹配邮箱地址
	 * 是邮箱地址返回1
	 * 不是返回0
	 */
	public static function  is_email($email_addr){
		$pattern = "/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i";
		$result=preg_match( $pattern, $email_addr );
		return  $result;
		
	}
	
	
	
}

//echo RegUtil::is_email("donghaifeng#hotmail.com");


?>
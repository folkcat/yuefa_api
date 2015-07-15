<?php
include_once './head/class.MyPDO.php';
class UserUtil {
	
	/*
	 * 插入一个用户到user_info表中，返回影响记录的条数。
	 * 成功返回1
	 * 失败返回0
	 */
	public static function create_user($email, $password) {
		$db = new MyPDO ();
		$str_query = "insert into user_info (email,password) values(:email,:password)";
		$result = $db->prepare ( $str_query );
		$num = $result->execute ( array (
				':email' => $email,
				':password' => $password 
		) );
		return $num;
	}
	/*
	 * 功能:用户登陆
	 * 参数：用户id 密码
	 * 返回值：0不存在该用户，1存在但密码错误，2正确
	 */
	public static function is_user($_email, $password) {
		$str_query = "select email,password from user_info where email=:email limit 1";
		$db = new MyPDO ();
		$result = $db->prepare ( $str_query );
		$result->execute ( array (
				':email' => $_email 
		) );
		$row = $result->fetch ();
		$email = $row ['email'];
		if (! $email) {
			return -2;//不存在该用户
		} else {
			if ($row ["password"] == $password) {
				
				return 1;//正确
			} else {
				
				return -3;//存在，但密码错误
			}
		}
		
	}
	
	/*
	 * 功能：获得用户昵称
	 * 参数：email
	 * 返回值，用户昵称
	 */
	public static function get_nick_name($email) {
		$str_query = "select display_name from user_info where email=:email limit 1";
		$db = new MyPDO ();
		$result = $db->prepare ( $str_query );
		$result->execute ( array (
				':email' => $email 
		) );
		$row = $result->fetch ();
		return $row ['display_name'];
	}
	
	/*
	 * 功能：用户认证
	 * 参数：session-id
	 * 返回值：1认证成功，0认证失败
	 *
	 */
	public static function verify($session_id) {
		$str_query = "select session_id from session where session_id=:id limit 1";
		$db = new MyPDO ();
		$result = $db->prepare ( $str_query );
		$result->execute ( array (
				':id' => $session_id
		) );
		$row = $result->fetch ();
		$exist = $row ['session_id'];
		if ($exist) {
			return 1;
		} else {
			return 0;
		}
	}
}

echo UserUtil::get_nick_name("donghaifeng@hotmail.com");

?>
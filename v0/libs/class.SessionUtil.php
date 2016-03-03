<?php
include_once './head/class.MyPDO.php';
class SessionUtil {
	/*
	 * 功能：插入一条session并返回session-id
	 * 参数：email电子邮箱地址，expire期限（日）
	 * 若成功：返回sesssion-id
	 * 若失败：返回0
	 */
	public static function create_session($email, $expire = 10) {
		$str_query = "insert into session(email,expire)values(:email,:expire)";
		$db = new MyPDO ();
		$result = $db->prepare ( $str_query );
		$result->execute ( array (
				':email' => $email,
				':expire' => $expire 
		) );
		$session_id = $db->lastInsertId ();
		return $session_id;
	}
	
	/*
	 * 功能：根据Session id返回电子邮箱地址
	 * 参数：session-id
	 * 若成功，返回email地址
	 * 若失败，返回0
	 */
	public static function get_email($session_id) {
		$str_query = "select email from session where id=:session_id limit 1";
		$db = new MyPDO ();
		$result = $db->prepare ( $str_query );
		$result->execute ( array (
				':session_id' => $session_id 
		) );
		$row = $result->fetch ();
		$email = $row ['email'];
		return $email;
	}
	
	/*
	 * 功能：删除一条session
	 * 参数：session-id
	 * 返回值，成功返回非0，失败返回0
	 *
	 * 注意，PDO没有预处理，传参之前要过滤！
	 */
	public static function delete_session($session_id) {
		$str_query = "delete from session where id=\"$session_id\" limit 1";
		$db = new MyPDO ();
		$num = $db->exec ( $str_query );
		return $num;
	}
}
// echo SessionUtil::delete_session(1001);

// echo SessionUtil::get_email(2000);
// echo SessionUtil::create_session("donghaifeng1@hotmail.com");

?>

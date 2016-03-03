<?php
include_once './head/class.MyPDO.php';

class UnNameUtil{
	public static function logException($page,$describe){
		$db=new MyPDO();
		$str_query = "insert into exception_log (page_,describe_) values(:page,:describe)";
		$result = $db->prepare ( $str_query );
		$num = $result->execute ( array (
				':page' => $page,
				':describe' => $describe
		) );
	}

}



?>
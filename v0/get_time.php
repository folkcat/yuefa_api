<?php 
error_reporting(0);
$client_time=$_GET['client_time'];

// 定义一个函数getIP() 
function getIP() { 
    $ip; 
    if (getenv("HTTP_CLIENT_IP")) 
        $ip = getenv("HTTP_CLIENT_IP"); 
    else if(getenv("HTTP_X_FORWARDED_FOR")) 
        $ip = getenv("HTTP_X_FORWARDED_FOR"); 
    else if(getenv("REMOTE_ADDR")) 
        $ip = getenv("REMOTE_ADDR"); 
    else 
        $ip = "Unknow"; 
    
    return $ip; 
} 

function getCurrentTime(){
    return time();
}
$result_arr=array("ip"=>getIP(),"server_time"=>getCurrentTime(),"client_time"=>$client_time);
echo json_encode($result_arr);

?> 
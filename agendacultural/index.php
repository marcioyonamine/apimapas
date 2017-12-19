<?php 
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);
$url = "http://culturaz.santoandre.sp.gov.br/api/event/findByLocation/";

// /agendacultural/MAPI/event/findByLocation/
//echo $_SERVER['QUERY_STRING']; 



$get_addr = $url.'?'.$_SERVER['QUERY_STRING'];


$ch = curl_init($get_addr);
$page = curl_exec($ch);
return $page;


/*
echo "<pre>";
var_dump($fp);
echo "</pre>";
*/

?>
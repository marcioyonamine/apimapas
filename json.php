<?php

$url = "http://spcultura.prefeitura.sp.gov.br/api/event/find";
$data = array("@select" => "id, name, longDescription, createTimestamp", "@order" => "name ASC", "owner" => "EQ(@Agent:608)");
$get_addr = $url.'?'.http_build_query($data);
$ch = curl_init($get_addr);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$page = curl_exec($ch);
echo "<pre>";
print_r(json_decode($page));
echo "</pre>";
?>

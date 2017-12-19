<?php

/*
Estudos para o uso da API dos mapas culturais com base no SPCultura
Documentação da API: https://github.com/hacklabr/mapasculturais/blob/master/doc/api.md
*/
/* 
  */
  
include "function.php";
include "conexao.php";  
  

$url = "http://culturaz.santoandre.sp.gov.br/api/event/findByLocation";
//$url = "http://culturaz.santoandre.sp.gov.br/api/event/find";


$hoje = date('Y-m-d');
$data = array(
   	"@from" => $hoje,
	"@to" => somarDatas($hoje,360),
	"@select" => "id, name, longDescription, terms, project, occurrences.{rule,space.{id,name,singleUrl,shortDescription}} ,createTimestamp, updateTimestamp,owner", 	"@seals" => "1,2,3",
	"@order" => "id ASC" 
	);
	

$get_addr = $url.'?'.http_build_query($data);
$ch = curl_init($get_addr);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$page = curl_exec($ch);

$evento = json_decode($page);

$ccsp = converterObjParaArray($evento);


echo "Foram encontrados ".sizeof($evento)." resultados.<br><br>";
echo $get_addr;


$ccsp = converterObjParaArray($evento);
/*
echo "<pre>";
print_r($ccsp);
echo "</pre>";
*/

echo "<pre>";
print_r($ccsp);
echo "</pre>";



?>
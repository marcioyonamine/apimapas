<?php 


function pegaJsonMapas($url){

	$expini = "var MapasCulturais = ";
	$contini = strlen($expini);
	$inicio = strpos($url,$expini);

	$texto_inicio = substr($url,$contini+$inicio);

	$expfim = '"canRelateSeal":true};';
	$contfim = strlen($expfim);
	$fim = strpos($texto_inicio,$expfim);
	$posicao_fim = $contfim + $fim;

	$caracteres_fim = strlen($texto_inicio) - $posicao_fim;

	$texto_final = substr($texto_inicio,0,-($caracteres_fim+1));

	return $texto_final;
}

$url = file_get_contents('teste.html');

var_dump(pegaJsonMapas($url));

$x = json_decode(pegaJsonMapas($url,true));
echo "<pre>";
var_dump($x,true);
echo "</pre>";

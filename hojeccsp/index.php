<?php 
/*
Estudos para o uso da API dos mapas culturais com base no SPCultura
Documentação da API: https://github.com/hacklabr/mapasculturais/blob/master/doc/api.md
*/
function diasemana($data) {
	$ano =  substr("$data", 0, 4);
	$mes =  substr("$data", 5, -3);
	$dia =  substr("$data", 8, 9);

	$diasemana = date("w", mktime(0,0,0,$mes,$dia,$ano) );

	switch($diasemana) {
		case"0": $diasemana = "Domingo";       break;
		case"1": $diasemana = "Segunda"; break;
		case"2": $diasemana = "Terça";   break;
		case"3": $diasemana = "Quarta";  break;
		case"4": $diasemana = "Quinta";  break;
		case"5": $diasemana = "Sexta";   break;
		case"6": $diasemana = "Sábado";        break;
	}

	return $diasemana;
}

function converterObjParaArray($data) { //função que transforma objeto vindo do json em array

    if (is_object($data)) {
        $data = get_object_vars($data);
    }

    if (is_array($data)) {
        return array_map(__FUNCTION__, $data);
    }
    else {
        return $data;
    }
}

$url = "http://spcultura.prefeitura.sp.gov.br/api/event/find";


$data = array(
	'@select' => 'id, name, longDescription, occurrences', 
	"@order" => "id ASC", 
	"owner" => "EQ(@Agent:608)"
	);

$get_addr = $url.'?'.http_build_query($data);
$ch = curl_init($get_addr);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$page = curl_exec($ch);

$evento = json_decode($page);

$hoje = date('Y-m-d');
$semana = date('w');
	$timestamp = strtotime($hoje); 
	$dia = date('d/m/Y',$timestamp);
	$hora = date('H:i', $timestamp);
//echo "Foram encontrados ".sizeof($evento)." resultados.<br><br>";

$ccsp = converterObjParaArray($evento);
?>

<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<!-- <meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no" /> -->
<meta name="referrer" content="default" id="meta_referrer" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<noscript>
<meta http-equiv="X-Frame-Options" content="deny" />
</noscript>
<title><?php

echo $dia." - ".diasemana($hoje); 
 ?></title>
<link rel="stylesheet" type="text/css" href="mobile.css">
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>

</head>
<body>

<div id='hoje'><center>
<img src="image/hojeccsp.png" /><br />
<?php echo $dia." - ".diasemana($hoje); ?></center></div>
<?php 

for($i = 0; $i < sizeof($evento); $i++){
	for($k = 0; $k < count($ccsp[$i]['occurrences']); $k++){
		if(
		($ccsp[$i]['occurrences'][$k]['rule']['startsOn'] == $hoje) OR //o dia exato de hoje
		(($ccsp[$i]['occurrences'][$k]['rule']['startsOn'] < $hoje) AND ($ccsp[$i]['occurrences'][$k]['rule']['until'] > $hoje) AND (isset($ccsp[$i]['occurrences'][$k]['rule']['day'][$semana])))
		){
			
				echo "
				<script type='text/javascript'>
				$(function() {
				$('#conteudo".$i."').click(function() {
					$('.conteudo".$i."').slideToggle('fast');
				return false;
				});
				});
				</script>
				";
				echo "<h2>+ <a href='#' id='conteudo".$i."'>".$ccsp[$i]['name'];
				echo "</a></h2>";
				echo "<div class='evento'>".$ccsp[$i]['occurrences'][$k]['rule']['startsAt'];
				//if($hoje['hora'] != $hoje['hora_final']){
					//echo " às ".$hoje['hora_final'];
					//}
				echo " - <i>".$ccsp[$i]['occurrences'][$k]['space']['name']."</i><br></h3></div><div class='conteudo".$i."' style='display:none; '>
				
				<p>".nl2br($ccsp[$i]['longDescription'])."</p>
				
				</div><br />";
					
				}

				
		}
	}

?>

<br />



<div id="ccsp"><a href="http://www.centrocultural.sp.gov.br"><img src="image/logos.png" alt="" width="300" height="69"  /></a></div>

</body>
</html>
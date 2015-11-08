<?php 

function publicar($id){
switch($id){

case 230:
case 544:
case 534:
case 541:

	return true;
	break;


default:
	return false;
	
}

	
}

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

function ordenaPontos( $a, $b ) {  
     if ( $a[$j]['horario'] == $b[$j]['horario'] ) {  
         return 0;  
     }  
     return ( $a[$j]['horario'] < $b[$j]['horario'] ) ? -1 : 1;  
 }  
 

$url = "http://spcultura.prefeitura.sp.gov.br/api/event/findByLocation";


$data = array(
   	"@from" => "2015-09-01",
	"@to" => "2015-09-30",
	'@select' => 'id, name, terms, occurrences', // aqui eu peço o id, nome, termos (linguagem) e ocorrências
	"@order" => "id ASC", 
	"owner" => "EQ(@Agent:608)" // do agente 608 (CCSP)
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

$ccsp = converterObjParaArray($evento); // aqui converto o objeto json em uma array

$j = 0;
for($i = 0; $i < sizeof($evento); $i++){
	for($k = 0; $k < count($ccsp[$i]['occurrences']); $k++){
		if(
		($ccsp[$i]['occurrences'][$k]['rule']['startsOn'] == $hoje) OR //o dia exato de hoje
		(($ccsp[$i]['occurrences'][$k]['rule']['startsOn'] < $hoje) AND ($ccsp[$i]['occurrences'][$k]['rule']['until'] > $hoje) AND (isset($ccsp[$i]['occurrences'][$k]['rule']['day'][$semana])))
		){
			
// eventos de hoje  - aqui gero uma array mais organizada
$evhoj[$j]['nome'] = $ccsp[$i]['name'];
$evhoj[$j]['valor'] = $ccsp[$i]['occurrences'][$k]['rule']['price'];
$evhoj[$j]['horario'] = $ccsp[$i]['occurrences'][$k]['rule']['startsAt'];
$evhoj[$j]['sala'] = $ccsp[$i]['occurrences'][$k]['space']['name'];
$evhoj[$j]['codSala'] = $ccsp[$i]['occurrences'][$k]['space']['id'];
$evhoj[$j]['projeto'] = 0;
$evhoj[$j]['linguagem'] = $ccsp[$i]['terms']['linguagem'][0];
$j++;			
				}

				
		}
	}



?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="cache-control" content="max-age=0" />
<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="expires" content="0" />
<meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
<meta http-equiv="pragma" content="no-cache" />
<link rel="stylesheet" type="text/css" href="igccsp.css">
<title>Bilheteria.CCSP</title>
</head>

<body>

<div id="TOPO">
  <div align="left">
  <script type="text/javascript">

var digital = new Date(); // crio um objeto date do javascript
//digital.setHours(15,59,56); // caso não queira testar com o php, comente a linha abaixo e descomente essa
digital.setHours(<?php echo date("H,i,s"); ?>); // seto a hora usando a hora do servidor
<!--
function clock() {
var hours = digital.getHours();
var minutes = digital.getMinutes();
var seconds = digital.getSeconds();
digital.setSeconds( seconds+1 ); // aquin que faz a mágica
// acrescento zero
if (minutes <= 9) minutes = "0" + minutes;
if (seconds <= 9) seconds = "0" + seconds;

dispTime = hours + ":" + minutes + ":" + seconds;
document.getElementById("clock").innerHTML = dispTime; // coloquei este div apenas para exemplo
setTimeout("clock()", 1000); // chamo a função a cada 1 segundo

}

window.onload = clock;
//-->

</script>
<!-- coloquei este div apenas para exemplo //-->

					BILHETERIA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span id="clock"></span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo date('d/m/Y') ?><font color="#FFF">
				
                   
  </div>
<table>
<?php
for($j = 0; $j < count($evhoj); $j++){
	if(publicar($evhoj[$j]['codSala']) == true){
 ?>
 
			<tr>
			<td><div class="programas"> <div class="genero"> <?php echo $evhoj[$j]['linguagem']; ?></div>
	   		 <div class="titulo"> <?php echo $evhoj[$j]['nome']; ?></div></div></td>
			<td><div class="LOCAL"><div class="sala"><?php  ?></div><?php echo substr($evhoj[$j]['sala'], 7);; ?><div class="lotacao"><?php echo "" ?> lugares</div></div> </td>
			<td><div class ="INGRESSOS"><div class="sala">R$</div><div class="ingresso"> <?php echo $evhoj[$j]['valor']; ?></div></div></td>

			<td><div class="horarios"><div class="H"><?php echo $evhoj[$j]['horario']; ?></div></div> </td>
			</tr>
<?php 
	}
} 
?>
</table>





</body>
</html>
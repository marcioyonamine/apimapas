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

					BILHETERIA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span id="clock"></span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? echo date('d/m/Y') ?><font color="#FFF">
				
                   
  </div>
<table>

<?
require 'conecta_mysql.php';
require 'utf.php';
include 'funcoes.php';

$contagem = 0;
$data_atual = date("Y-m-d");
$hora_atual = date("Y-m-d H:i:s");
$hora_tolerancia = "15 minutes";
$horaNova = strtotime("$hora_atual - $hora_tolerancia");
$horaNovaFormatada = date("H:i:s",$horaNova);


$dia_semana_atual = diasemana($data_atual);
$sql = "SELECT * FROM evento WHERE confirma = 'Publicado' ORDER BY horario ASC";
$lista = mysql_query($sql);
while($campo = mysql_fetch_array($lista)){
	if($campo['data_final'] == 0000-00-00){
		if($campo['data_inicial'] == $data_atual){
			$mysql_hora = $campo['horario'];
			$timestamp = strtotime($mysql_hora); 
			$id_genero = $campo['genero'];
			$sql_genero = "SELECT * FROM genero WHERE id='$id_genero'";
			$sql_query = mysql_query($sql_genero);
			$genero = mysql_fetch_array($sql_query);
 
		?>
        <? if($mysql_hora > $horaNovaFormatada ){ ?>
			<tr>
			<td><div class="programas"> <div class="genero"> <? echo $genero['genero']; ?></div>
	   		 <div class="titulo"> <? echo $campo['evento']; ?></div></div></td>
			<td><div class="LOCAL"><div class="sala"><? tipo_sala($campo['sala']); ?></div><? sala($campo['sala']); ?><div class="lotacao"><? echo $campo['lotacao']; ?> lugares</div></div> </td>
			<td><div class ="INGRESSOS"><div class="sala">R$</div><div class="ingresso"> <? echo $campo['valor_ingresso']; ?></div></div></td>

			<td><div class="horarios"><div class="H"><? echo date('H:i', $timestamp); ?></div></div> <? $contagem++; ?></td>
			</tr>
            <? } ?>
        <?
		}else{
			//echo "Eventos de 1 dia que não acontecem hoje: ";
			//echo $campo['evento']."<br />";
		}
	} else {
		if(($campo['data_inicial'] <= $data_atual) && ($campo['data_final'] >= $data_atual)){
			if($campo[$dia_semana_atual] == 1){
					$mysql_hora = $campo['horario'];
					$timestamp = strtotime($mysql_hora); 
					$id_genero = $campo['genero'];
					$sql_genero = "SELECT * FROM genero WHERE id='$id_genero'";
					$sql_query = mysql_query($sql_genero);
					$genero = mysql_fetch_array($sql_query);

				?>
                <? if($mysql_hora > $horaNovaFormatada ){ ?>
	<tr>
	<td><div class="programas"> <div class="genero"> <? echo $genero['genero']; ?></div>
	    <div class="titulo"> <? echo $campo['evento']; ?></div></div></td>
	<td><div class="LOCAL"><div class="sala"><? tipo_sala($campo['sala']); ?></div><? sala($campo['sala']); ?><div class="lotacao"><? echo $campo['lotacao']; ?> lugares</div></div> </td>
	<td><div class ="INGRESSOS"><div class="sala">R$</div><div class="ingresso"> <? 
		if($campo['especial_dia'] == $data_atual){
			echo $campo['especial_ingresso'];
		}else{
			echo $campo['valor_ingresso'];
		}
	?></div></div></td>

	<td><div class="horarios"><div class="H"><? echo date('H:i', $timestamp); ?></div></div> <? $contagem++; ?></td>
	</tr>
    <? } ?>
				<?
			}else{
			//echo "Eventos de temporada que estão em plena temporada mas não acontecem hoje! ";
			//echo $campo['evento']."<br />";

			}
		}else{
			//echo "Eventos de temporada que não estão em temporada: ";
			//echo $campo['evento']."<br />";

		}
	}
}

?>
</table>
<? 


if($contagem >= 5)
{?>
<script>
setTimeout("window.location.href='display2.php';",20000);
</script>
<? }

if($contagem <= 3){ ?>

<div id="RODAPE">
<marquee behavior=slide direction=left><?
 		$sql = "SELECT * FROM mensagem";
		$sql_query = mysql_query($sql);
		while($campo = mysql_fetch_array($sql_query)){
			$mensagem = $campo['mensagem'];
			echo $mensagem;
		} ?></marquee>
</div>

<? } ?>

</body>
</html>
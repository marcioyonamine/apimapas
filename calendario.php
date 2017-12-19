<?php
	set_time_limit(200);	
/*
Estudos para o uso da API dos mapas culturais com base no SPCultura
Documentação da API: https://github.com/hacklabr/mapasculturais/blob/master/doc/api.md

Na tabela mapas, o valor do tipo é: 1 => evento, 2 => espaço, 3 => agente, 4=> projeto 


*/


////////////////////Funcoes
  
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

function chamaAPI($url,$data){
	$url = 
	$get_addr = $url.'?'.http_build_query($data);
	$ch = curl_init($get_addr);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$page = curl_exec($ch);
	$evento = json_decode($page);
	$ccsp = converterObjParaArray($evento);
	return $ccsp;
	
}

function bancoMysqli(){ 
	$servidor = 'localhost';
	$usuario = 'root';
	$senha = '';
	$banco = 'agenda';
	$con = mysqli_connect($servidor,$usuario,$senha,$banco); 
	mysqli_set_charset($con,"utf8");
	return $con;
}

function verificaMapas($tipo,$mapas){
	$con = bancoMysqli();
	
	$sql = "SELECT valor FROM mapas WHERE id_mapas = '$mapas' AND tipo = '$tipo'";
	$query = mysqli_query($con,$sql);
	$num = mysqli_num_rows($query);
	if($num > 0){ // existe o id
		return TRUE;	
	}else{
		return FALSE;
	}
}

function retornaMapas($tipo,$mapas){
	$con = bancoMysqli();
	$sql = "SELECT valor FROM mapas WHERE id_mapas = '$mapas' AND tipo = '$tipo'";
	$query = mysqli_query($con,$sql);
	$num = mysqli_num_rows($query);
	if($num > 0){ // existe o id
		$x = mysqli_fetch_array($query);
		return $x['valor'];	
	}else{
		return FALSE;
	}

	
}

	function diasemana($data)
	{
		$ano =  substr("$data", 0, 4);
		$mes =  substr("$data", 5, -3);
		$dia =  substr("$data", 8, 9);
		$diasemana = date("w", mktime(0,0,0,$mes,$dia,$ano) );
		switch($diasemana)
		{
			case"0": $diasemana = "Domingo";       break;
			case"1": $diasemana = "Segunda-Feira"; break;
			case"2": $diasemana = "Terça-Feira";   break;
			case"3": $diasemana = "Quarta-Feira";  break;
			case"4": $diasemana = "Quinta-Feira";  break;
			case"5": $diasemana = "Sexta-Feira";   break;
			case"6": $diasemana = "Sábado";        break;
		}
		return "$diasemana";
	}

	function diasemananumero($data)
	{
		$ano =  substr("$data", 0, 4);
		$mes =  substr("$data", 5, -3);
		$dia =  substr("$data", 8, 9);
		$diasemana = date("w", mktime(0,0,0,$mes,$dia,$ano) );
		return "$diasemana";
	}

function horaMapas($string){
	return substr($string,11, 5);	
}

function select($a,$b){
	if($a == $b){
		echo "selected";	
	}	
}

//////////////////////////////////
if(isset($_GET['mes'])){
	$mes = $_GET['mes'];
}else{
	$mes = date('m');	
}

if(isset($_GET['ano'])){
	$ano = $_GET['ano'];
}else{
	$ano = date('Y');	
}

if(isset($_GET['pag'])){
	$pag = $_GET['pag'];	
}



$numero = cal_days_in_month(CAL_GREGORIAN, 10, 2017); // 31
$url_mapas = "http://culturaz.santoandre.sp.gov.br/api/";
$url = $url_mapas."event/findByLocation";

switch($pag){

case "config":

// Atualiza os espaços
$url_space = $url_mapas."space/find";
$data = array(
	"@select" => "id, name",
	"@seals" => "1,2,3"
	//"owner" => "IN(870,105)",
	//"isVerified" => TRUE
	//"@order" => "id ASC"
	);

$x = chamaAPI($url_space,$data);
$con = bancoMysqli();

for($i = 0; $i < count($x); $i++){
	$y = verificaMapas(2,$x[$i]['id']);
	if($y == FALSE){
		$sql = "INSERT INTO `mapas` (`id`, `id_mapas`, `tipo`, `valor`) VALUES (NULL, '".$x[$i]['id']."', '2', '".$x[$i]['name']."')"
;
		$query = mysqli_query($con,$sql);
		if($query){
			echo $x[$i]['name']." inserido.<br />";	
		}else{
			echo $sql."<br />";	
		}
	}else{
		var_dump($y);	
	}
	
}


// 



break;


default:	

?>
<?php
$dia = array();

for($i = 1; $i <= $numero; $i++){
	$data = array(
 	"@from" => "$ano-$mes-$i",
	"@to" => "$ano-$mes-$i",
	"@select" => "id, name, occurrences",
	"@seals" => "1,2,3"
	//"owner" => "IN(870,105)",
	//"isVerified" => TRUE
	//"@order" => "id ASC"
	);

	$get_addr = $url.'?'.http_build_query($data);
	$ch = curl_init($get_addr);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$page = curl_exec($ch);
	
	$evento = json_decode($page);
	$ccsp = converterObjParaArray($evento);
	$url_endereco = "http://culturaz.santoandre.sp.gov.br/api/eventOccurrence/find";
	$nome = "";
	
	for($k = 0; $k < sizeof($evento); $k++){
		//echo $ccsp[$i]['occurrences'][$k]['rule']['startsOn'];
		if($nome != $ccsp[$k]['name']){
			
			$data = array(
			"@select" => "space,_startsAt",
			"@seals" => "1,2,3",
			"id" => "EQ(".$ccsp[$k]['occurrences'][0].")"
			);
				
			$endereco = chamaAPI($url_endereco,$data);
			if(isset($_GET['espaco']) AND $_GET['espaco'] ==  $endereco[0]['space']){
				//echo $ccsp[$k]['name']."<br />";
				//echo retornaMapas(2,$endereco[0]['space'])."<br /><br />";
			}

				$dia[$i][$k]['id'] = $ccsp[$k]['id'];			
				$dia[$i][$k]['evento'] = $ccsp[$k]['name'];
				$dia[$i][$k]['inicio'] = horaMapas($endereco[0]['_startsAt']['date']);
				$dia[$i][$k]['id_espaco'] = $endereco[0]['space'];
				$dia[$i][$k]['espaco'] = retornaMapas(2,$endereco[0]['space']);
		}
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Agenda SC - Santo André</title>
</head>

<body>
<style>
td {
    vertical-align: top;	
}

</style>
<center><h1>Agenda Geral de Eventos da Secretaria de Cultura - Prefeitura de Santo André</h1></center>
<p>Esta agenda é gerada a partir da plataforma CulturAZ (http://culturaz.santoandre.sp.gov.br) com eventos que tenham selos de certificação</p>
<form action="?" method="get">
<select name="mes">
<option value="1" <?php select(1,$mes); ?>>Janeiro</option>
<option value="2" <?php select(2,$mes); ?>>Fevereiro</option>
<option value="3" <?php select(3,$mes); ?>>Março</option>
<option value="4" <?php select(4,$mes); ?>>Abril</option>
<option value="5" <?php select(5,$mes); ?>>Maio</option>
<option value="6" <?php select(6,$mes); ?>>Junho</option>
<option value="7" <?php select(7,$mes); ?>>Julho</option>
<option value="8" <?php select(8,$mes); ?>>Agosto</option>

<option value="9" <?php select(9,$mes); ?>>Setembro</option>
<option value="10" <?php select(10,$mes); ?>>Outubro</option>
<option value="11" <?php select(11,$mes); ?>>Novembro</option>
<option value="12" <?php select(12,$mes); ?>>Dezembro</option>
</select>
<form>
<select name="ano">
<option value="2017"  <?php select(2017,$ano); ?>>2017</option>
<option value="2018"  <?php select(2018,$ano); ?>>2018</option>
</select>
<select name="espaco">
<option value="0">Todos os espaços da SC</option>
<?php 
$con = bancoMysqli();
$sql_espaco = "SELECT * FROM mapas WHERE tipo = '2' ORDER BY valor";
$query_espaco = mysqli_query($con,$sql_espaco);
while($esp = mysqli_fetch_array($query_espaco)){
	if(isset($_GET['espaco'])){
		if($_GET['espaco'] == $esp['id_mapas']){	
			echo "<option value='".$esp['id_mapas']."' selected='selected'>".$esp['valor']."</option>";	
		}else{
			echo "<option value='".$esp['id_mapas']."'>".$esp['valor']."</option>";	
		}
	}else{
	echo "<option value='".$esp['id_mapas']."'>".$esp['valor']."</option>";	
		
	}
}

?>
</select>
<input type="submit" value="filtrar" />

</form>



<table border="1" style="vertical-align: top;">
  <tr>
    <th colspan="7" scope="col">Mês <?php echo $mes;?></th>
  </tr>
  <tr>
    <td width="14%">dom</td>
    <td width="14%">seg</td>
    <td width="14%">ter</td>
    <td width="14%">qua</td>
    <td width="14%">qui</td>
    <td width="14%">sex</td>
    <td width="14%">sab</td>
  </tr>


<?php $dia_primeiro = diasemananumero(date("Y-".$mes."-01")); 
$branco =  "<td>0</td>";
$d = 1;
$url_mapas = "http://culturaz.santoandre.sp.gov.br";
//primeira linha
echo "<tr>";
for($k = 0; $k < $dia_primeiro; $k++){
	echo $branco;	
}
for($k = $dia_primeiro; $k <= 6; $k++){
	echo "<td><h2>$d</h2>";
	for($p = 0; $p < count($dia[$d]); $p++){
		if(isset($_GET['espaco']) AND $_GET['espaco'] != 0){
			if($_GET['espaco'] == $dia[$d][$p]['id_espaco']){
		echo $dia[$d][$p]['inicio']."<br />";
		echo "<a href='".$url_mapas."\/evento\/".$dia[$d][$p]['id']."' target='_blank'>".$dia[$d][$p]['evento']."</a><br />";
		echo "Local: <a href='".$url_mapas."\/espaco\/".$dia[$d][$p]['id_espaco']."' target='_blank'>".$dia[$d][$p]['espaco']."</a><br /><br />";
			}
		}else{
		echo $dia[$d][$p]['inicio']."<br />";
		echo "<a href='".$url_mapas."\/evento\/".$dia[$d][$p]['id']."' target='_blank'>".$dia[$d][$p]['evento']."</a><br />";
		echo "Local: <a href='".$url_mapas."\/espaco\/".$dia[$d][$p]['id_espaco']."' target='_blank'>".$dia[$d][$p]['espaco']."</a><br /><br />";
			
		}
	}
	echo "</td>";
	$d++;
}

echo "</tr>";


while($d < $numero){
echo "<tr>";
for($k = 0; $k < 7; $k++){
	if($d <= $numero){
	echo "<td><h2>$d</h2>";
	for($p = 0; $p < count($dia[$d]); $p++){
		if(isset($_GET['espaco']) AND $_GET['espaco'] != 0 ){
			if($_GET['espaco'] == $dia[$d][$p]['id_espaco']){
		echo $dia[$d][$p]['inicio']."<br />";
		echo "<a href='".$url_mapas."\/evento\/".$dia[$d][$p]['id']."' target='_blank'>".$dia[$d][$p]['evento']."</a><br />";
		echo "Local: <a href='".$url_mapas."\/espaco\/".$dia[$d][$p]['id_espaco']."' target='_blank'>".$dia[$d][$p]['espaco']."</a><br /><br />";
			}
		}else{
		echo $dia[$d][$p]['inicio']."<br />";
		echo "<a href='".$url_mapas."\/evento\/".$dia[$d][$p]['id']."' target='_blank'>".$dia[$d][$p]['evento']."</a><br />";
		echo "Local: <a href='".$url_mapas."\/espaco\/".$dia[$d][$p]['id_espaco']."' target='_blank'>".$dia[$d][$p]['espaco']."</a><br /><br />";
			
		}
	}
	echo "</td>";
	$d++;

	}else{
		echo "<td></td>";
	}
}
echo "</tr>";
}



?>
  
  
  
</table>


<?php
break;
}


	?>
</body>
</html>




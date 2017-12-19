<?php 
include "function.php";
include "conexao.php";
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

if(isset($_GET['espaco'])){
	if($_GET['espaco']){
		$espaco = "";	
	}else{
		$espaco = " AND id_local = '".$_GET['espaco']."' ";		
	}
}else{
	$espaco = "";	
}


$numero = cal_days_in_month(CAL_GREGORIAN, $mes, $ano); // 31
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- As 3 meta tags acima *devem* vir em primeiro lugar dentro do `head`; qualquer outro conteúdo deve vir *após* essas tags -->
    <title>Agenda de Eventos - Secretaria de Cultura de Santo André</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim e Respond.js para suporte no IE8 de elementos HTML5 e media queries -->
    <!-- ALERTA: Respond.js não funciona se você visualizar uma página file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
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
$branco =  "<td></td>";
$d = 1;
$url_mapas = "http://culturaz.santoandre.sp.gov.br";

//primeira linha
echo "<tr>";
for($k = 0; $k < $dia_primeiro; $k++){
	echo $branco;	
}
for($k = $dia_primeiro; $k <= 6; $k++){
	echo "<td><h2>$d</h2>";
	$sql_busca = "SELECT * FROM agenda WHERE dia = '$ano-$mes-$d' $espaco ORDER BY hora_inicio ASC ";
	$query_busca = mysqli_query($con,$sql_busca);
	while($event = mysqli_fetch_array($query_busca)){
			echo "<p>".horaMysql($event['hora_inicio'])."<br />";
			echo "<a href='$url_mapas/evento/".$event['id_evento']."'>";	
			echo retornaMapas(1,$event['id_evento'])."</a><br />";
			echo "Local: ".retornaMapas(2,$event['id_local']);
		
		"</p>";
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
	$sql_busca = "SELECT * FROM agenda WHERE dia = '$ano-$mes-$d' ORDER BY hora_inicio ASC";
	$query_busca = mysqli_query($con,$sql_busca);
	while($event = mysqli_fetch_array($query_busca)){
			echo "<p>".horaMysql($event['hora_inicio'])."<br />";
			echo "<a href='$url_mapas/evento/".$event['id_evento']."'>";	
			echo retornaMapas(1,$event['id_evento'])."</a><br />";
			echo "Local: ".retornaMapas(2,$event['id_local']);
		
		"</p>";
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
    <!-- jQuery (obrigatório para plugins JavaScript do Bootstrap) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Inclui todos os plugins compilados (abaixo), ou inclua arquivos separadados se necessário -->
    <script src="js/bootstrap.min.js"></script>

</body>
</html>




<?php

/*
Estudos para o uso da API dos mapas culturais com base no SPCultura
Documentação da API: https://github.com/hacklabr/mapasculturais/blob/master/doc/api.md
*/
  
include "function.php";
include "conexao.php";

set_time_limit(0);
	$antes = strtotime(date('Y-m-d H:i:s')); // note que usei hífen
	echo "<h1>Atualizando Agenda...</h1><br />";

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


$con = bancoMysqli();

/////////// Atualiza os nomes dos eventos
for($i = 0; $i < count($ccsp); $i++){ // roda todos os eventos (1)
	$evento = $ccsp[$i]['name'];
	$id_evento = $ccsp[$i]['id'];
	$x = verificaMapas(1,$id_evento);
	if($x == TRUE){ // atualiza
		$sql = "UPDATE mapas SET
		valor = '$evento'
		WHERE id_mapas = '$id_evento'
		AND tipo = '1'";
		$query = mysqli_query($con,$sql);
		if($query){
			echo "Evento $evento atualizado.<br />";	
		}else{
			echo "$evento - erro (1)<br />";	
		}
		
	}else{ //insere
		$sql = "INSERT INTO mapas (id_mapas,tipo,valor)
		 VALUES ('$id_evento','1','$evento')";
		 $query = mysqli_query($con,$sql);
		 if($query){
			echo "Evento $evento inserido.<br />";	
		}else{
			echo "$evento - erro (2)<br />";	
		}
	}
}

/////////// Atualiza os nomes dos espaços
$url_mapas = "http://culturaz.santoandre.sp.gov.br/api/";
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

$numero = cal_days_in_month(CAL_GREGORIAN, $mes, $ano); // 31

////////// Atualização do Calendário
for($i = 0; $i < count($ccsp); $i++){ // roda todos os eventos (1)
	$e = $ccsp[$i]['occurrences'];
	$id_evento = $ccsp[$i]['id'];
	$id_agente = $ccsp[$i]['owner'];
	for($k = 0; $k < count($e); $k++){ //roda as ocorrências (2)
		switch($e[$k]['rule']['frequency']){ //roda a switch (3)

		case 'once':
		$dia = $e[$k]['rule']['startsOn'];
		$hora_inicio = $e[$k]['rule']['startsAt'];
		$duracao = $e[$k]['rule']['duration'];
		$id_local = $e[$k]['space']['id'];
		$id_ocorrencia = $e[$k]['id'];
		
		if($ccsp[$i]['updateTimestamp'] == ""){
			$date_update = $ccsp[$i]['createTimestamp']['date'];	
		}else{
			$date_update = $ccsp[$i]['updateTimestamp']['date'];	
		}

		$ver = verificaDia($id_evento, $dia, $hora_inicio, $id_local, $duracao);	
			if($ver == TRUE){ // se existe atualiza
				$up = verificaUpdate($id_evento, $date_update);
				if($up == FALSE){ // verifica as datas
					$x = sqlAgenda($dia, $hora_inicio, $id_local, $duracao,$id_evento,$id_agente, $date_update, $id_ocorrencia,1);
			}
				
			}else{ // se não, insere
				$x = sqlAgenda($dia, $hora_inicio, $id_local, $duracao,$id_evento,$id_agente, $date_update, $id_ocorrencia);
				
			} 
		break;

		case 'daily':
				
		$dia = $e[$k]['rule']['startsOn'];
		$fim = somarDatas($e[$k]['rule']['until'],"+1");
		$hora_inicio = $e[$k]['rule']['startsAt'];
		$duracao = $e[$k]['rule']['duration'];
		$id_local = $e[$k]['space']['id'];
		$id_ocorrencia = $e[$k]['id'];			
		
		while($dia != $fim){

			
			if($ccsp[$i]['updateTimestamp'] == ""){
				$date_update = $ccsp[$i]['createTimestamp']['date'];	
			}else{
				$date_update = $ccsp[$i]['updateTimestamp']['date'];	
			}
	
			$ver = verificaDia($id_evento, $dia, $hora_inicio, $id_local, $duracao);	
			if($ver == TRUE){ // se existe atualiza
				$up = verificaUpdate($id_evento, $date_update);
				if($up == FALSE){ // verifica as datas
					$x = sqlAgenda($dia, $hora_inicio, $id_local, $duracao,$id_evento,$id_agente, $date_update, $id_ocorrencia,1);
				}
			}else{ // se não, insere
				$x = sqlAgenda($dia, $hora_inicio, $id_local, $duracao,$id_evento,$id_agente, $date_update, $id_ocorrencia);
			} 
			$dia = somarDatas($dia,"+1");


		}
				
		


		
		break;

		case 'weekly':

		$dia = $e[$k]['rule']['startsOn'];
		$fim = somarDatas($e[$k]['rule']['until'],"+1");
		$hora_inicio = $e[$k]['rule']['startsAt'];
		$duracao = $e[$k]['rule']['duration'];
		$id_local = $e[$k]['space']['id'];
		$id_ocorrencia = $e[$k]['id'];			
		$sem_array = array();
		foreach($e[$k]['rule']['day'] as $key){
			array_push($sem_array, key($e[$k]['rule']['day']));
		}
		
		while($dia != $fim){
		$semana = date('w', strtotime($dia));

		if(in_array($semana,$sem_array)){
			
			if($ccsp[$i]['updateTimestamp'] == ""){
				$date_update = $ccsp[$i]['createTimestamp']['date'];	
			}else{
				$date_update = $ccsp[$i]['updateTimestamp']['date'];	
			}
	
			$ver = verificaDia($id_evento, $dia, $hora_inicio, $id_local, $duracao);	
			if($ver == TRUE){ // se existe atualiza
				$up = verificaUpdate($id_evento, $date_update);
				if($up == FALSE){ // verifica as datas
					$x = sqlAgenda($dia, $hora_inicio, $id_local, $duracao,$id_evento,$id_agente, $date_update, $id_ocorrencia,1);
				}
			}else{ // se não, insere
				$x = sqlAgenda($dia, $hora_inicio, $id_local, $duracao,$id_evento,$id_agente, $date_update, $id_ocorrencia);
			} 
			
		}
			$dia = somarDatas($dia,"+1");


		}
				
	

		
		break;
		
		/*
		case 'monthly':
		
		break;


		case 'yearly':
		
		break;
		*/
		} // switch (3)
	} // (2)
	
} // (1)

	$depois = strtotime(date('Y-m-d H:i:s'));
	$tempo = $depois - $antes;
	echo "<br /><br /> Agenda atualizada em  $tempo segundos";

echo "<pre>";
print_r($ccsp);
echo "</pre>";



?>
<?php 

date_default_timezone_set('America/Sao_Paulo');

////////////////////Funcoes

//função que transforma objeto vindo do json em array  
function converterObjParaArray($data) { 
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

// roda API de leitura via curl
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

// verifica se existe na tabela mapas
/*
1 - evento
2 - espaços
3 - agentes
4 - projetos
5 - ocorrências


*/
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

// retorna as infos da tabela mapas
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


function diasemana($data) {
	$ano =  substr("$data", 0, 4);
	$mes =  substr("$data", 5, -3);
	$dia =  substr("$data", 8, 9);
	$diasemana = date("w", mktime(0,0,0,$mes,$dia,$ano) );
	
	switch($diasemana){
		
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

function diasemananumero($data) {
	$ano =  substr("$data", 0, 4);
	$mes =  substr("$data", 5, -3);
	$dia =  substr("$data", 8, 9);
	$diasemana = date("w", mktime(0,0,0,$mes,$dia,$ano) );
	
	return $diasemana;
}


function horaMapas($string){
	return substr($string,11, 5);	
}

function horaMysql($string){
	return substr($string,0, 5);	
}


function select($a,$b){
	if($a == $b){
		echo "selected";	
	}	
}

//soma(+) ou substrai(-) dias de um date(a-m-d)
function somarDatas($data,$dias){
	$data_final = date('Y-m-d', strtotime("$dias days",strtotime($data)));	
		return $data_final;
}


// verifica se a data existe
function verificaDia($id_evento, $data, $hora, $local, $duracao){
	$con = bancoMysqli();
	$sql = "SELECT id FROM agenda WHERE
		id_evento = '$id_evento' AND
		dia = '$data' AND
		hora_inicio = '$hora' AND
		id_local = '$local' AND
		duracao = '$duracao'";
	$query = mysqli_query($con,$sql);
	$n = mysqli_num_rows($query);
	if($n > 0){
		return TRUE;	
	}else{
		return FALSE;		
	}
}

// verifica atualizacao

function verificaUpdate($id_evento, $date_update){
	$con = bancoMysqli();

	$sql = "SELECT id FROM agenda WHERE
		id_evento = '$id_evento' AND
		date_update = '$date_update'";
	$query = mysqli_query($con,$sql);
	$n = mysqli_num_rows($query);
	if($n > 0){
		return TRUE;	
	}else{
		return FALSE;		
	}
}

// atualiza a base
function sqlAgenda($dia, $hora_inicio, $id_local, $duracao,$id_evento,$id_agente, $date_update, $id_ocorrencia, $atualiza = NULL){
	$con = bancoMysqli();
	if($atualiza == NULL){ //insere
		$sql = "INSERT INTO `agenda` (`id`, `id_evento`, `dia`, `hora_inicio`, `duracao`, `id_local`, `id_agente`, `id_ocorrencia`, `date_update`) 
		VALUES (NULL, '$id_evento', '$dia', '$hora_inicio', '$duracao', '$id_local', '$id_agente', '$id_ocorrencia', '$date_update')";
		$query = mysqli_query($con,$sql);
		if($query){
			return mysqli_insert_id($con);	
		}else{
			return FALSE;	
		}		
		
	}else{ // atualiza
		$sql_deleta = "DELETE FROM `agenda` WHERE id_evento = '$id_evento' AND dia =  '$dia'";
		$query_deleta = mysqli_query($con,$sql_deleta);
		if($query_deleta){
			$sql = "INSERT INTO `agenda` (`id`, `id_evento`, `dia`, `hora_inicio`, `duracao`, `id_local`, `id_agente`, `id_ocorrencia`, `date_update`) 
			VALUES (NULL, '$id_evento', '$dia', '$hora_inicio', '$duracao', '$id_local', '$id_agente', '$id_ocorrencia', '$date_update')";
			$query = mysqli_query($con,$sql);
			if($query){
				return mysqli_insert_id($con);	
			}else{
				return FALSE;	
			}		
		}
	}
}

<?php

/*
Estudos para o uso da API dos mapas culturais com base no SPCultura
Documentação da API: https://github.com/hacklabr/mapasculturais/blob/master/doc/api.md
*/

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
	"@select" => "id, name, longDescription, occurrences", 
	"@order" => "id ASC", 
	"owner" => "EQ(@Agent:608)"
	);

$get_addr = $url.'?'.http_build_query($data);
$ch = curl_init($get_addr);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$page = curl_exec($ch);

$evento = json_decode($page);


echo "Foram encontrados ".sizeof($evento)." resultados.<br><br>";

$ccsp = converterObjParaArray($evento);
/*
echo "<pre>";
print_r($ccsp);
echo "</pre>";
*/

// Retorna os eventos de hoje (converter a string date para date e fazer a comparação de datas)
$hoje = date('Y-m-d');
$semana = date('w');
echo "Hoje é $hoje<br>";

for($i = 0; $i < sizeof($evento); $i++){
	for($k = 0; $k < count($ccsp[$i]['occurrences']); $k++){
		if(
		($ccsp[$i]['occurrences'][$k]['rule']['startsOn'] == $hoje) OR //o dia exato de hoje
		(($ccsp[$i]['occurrences'][$k]['rule']['startsOn'] < $hoje) AND ($ccsp[$i]['occurrences'][$k]['rule']['until'] > $hoje) AND (isset($ccsp[$i]['occurrences'][$k]['rule']['day'][$semana])))
		){
			
			echo $ccsp[$i]['name'];	
				
		}
	}
}
//echo $ccsp[0]['occurrences'][0]['rule']['startsOn']; //Exemplo de acesso a informação

echo "<pre>";
print_r($ccsp);
echo "</pre>";


?>

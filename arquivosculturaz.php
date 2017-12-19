<?php

/*
Estudos para o uso da API dos mapas culturais com base no SPCultura
Documentação da API: https://github.com/hacklabr/mapasculturais/blob/master/doc/api.md
*/
/* 
  */


if(isset($_POST['buscar'])){
	if($_POST['inicio'] == "" AND $_POST['final'] == ""){
		$mensagem = "Ambos os campos necessitam ser preenchidos!";
	}else{
		
$url = "http://culturaz.santoandre.sp.gov.br/api/event/findByLocation";
//$url = "http://culturaz.santoandre.sp.gov.br/api/event/find";

$data = array(
   	"@from" => "2017-09-01",
	"@to" => "2017-09-30",
	"@select" => "id, name, longDescription, terms, project, occurrences", 
	"@files" => "(header):url",
	"@order" => "id ASC"
	
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


# create new zip object
$zip = new ZipArchive();

# create a temp file & open it
$tmp_file = "culturaz.zip";
$zip->open($tmp_file, ZipArchive::CREATE);

for($i = 0; $i < sizeof($evento); $i++){
	
	//for($k = 0; $k < count($ccsp[$i]['occurrences']); $k++){
		//if(
		//($ccsp[$i]['occurrences'][$k]['rule']['startsOn'] == $hoje) OR //o dia exato de hoje
		//(($ccsp[$i]['occurrences'][$k]['rule']['startsOn'] < $hoje) AND ($ccsp[$i]['occurrences'][$k]['rule']['until'] > $hoje) AND (isset($ccsp[$i]['occurrences'][$k]['rule']['day'][$semana])))
		//){
			
			echo $ccsp[$i]['name']."<br />";
			if($ccsp[$i]['@files:header']['url'] ==  true){
							   # download file
    			$download_file = file_get_contents($ccsp[$i]['@files:header']['url']);

   				 #add it to the zip
   				 $zip->addFromString(basename($ccsp[$i]['@files:header']['url']), $download_file);
			}
				
		//}
	//}
}
//echo $ccsp[0]['occurrences'][0]['rule']['startsOn']; //Exemplo de acesso a informação

/* Area de Zip */



/*
# loop through each file
foreach ($files as $file) {
    # download file
    $download_file = file_get_contents($file);

    #add it to the zip
    $zip->addFromString(basename($file), $download_file);
}

*/



# close zip
$zip->close();

# send the file to the browser as a download

	}
}
?>

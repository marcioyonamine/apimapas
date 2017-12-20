<html>
<head>
   <title>Basic Upload</title>
</head>
<body>
   <form action="#" method="POST" enctype="multipart/form-data">
      <input type="file" name="fileUpload">
      <input type="submit" value="Enviar">
   </form>


<?php
function mapas($id){
	$url = "http://culturaz.santoandre.sp.gov.br/api/agent/find";
	$data = array(
   	"@select" => "name,email,emailPublico",
    "id" => "eq(".$id.")"
	);
	$get_addr = $url.'?'.http_build_query($data);
	//echo $get_addr;
	$ch = curl_init($get_addr);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$page = curl_exec($ch);
	$evento = json_decode($page,true);
	return $evento;
}

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


   if(isset($_FILES['fileUpload']))
   set_time_limit(0);
	   {
      date_default_timezone_set("Brazil/East"); //Definindo timezone padrão

      $ext = strtolower(substr($_FILES['fileUpload']['name'],-4)); //Pegando extensão do arquivo
      $new_name = date("YmdHis") . $ext; //Definindo um novo nome para o arquivo
      $dir = 'uploads/'; //Diretório para uploads

      move_uploaded_file($_FILES['fileUpload']['tmp_name'], $dir.$new_name); //Fazer upload do arquivo
   
   
$url = file_get_contents($dir.$new_name);

$json = pegaJsonMapas($url);

$json_array = json_decode($json,true);

$x = $json_array['entity']['registrations'];
    
?>
<table border='1'>

<?php
for($i = 0; $i < count($x); $i++){
	$owner = $x[$i]['owner']['id'];
	$status = $x[$i]['status'];
	//echo $owner." / ".$status."<br />";
	$y = mapas($owner);
	if($status == 0){
		$s = "Rascunho";	
	}else{
		$s = "Enviado";
	}
	?>
	<tr>
	<td><?php echo $y[0]['name']?> </td>
	<td><?php echo $y[0]['emailPublico']?></td>
	<td><?php echo $y[0]['email']?></td>
	<td><?php echo $s?></td>
	</tr>
	<?php

	
}



?>
</table>
	   <?php } ?>
</body>
</html>
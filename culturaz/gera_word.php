
<?php 
include "funcoes.php";

if(isset($_POST['buscar'])){
	if(($_POST['inicio'] == "" AND $_POST['final'] == "") ){
		$mensagem = "Ambos os campos necessitam ser preenchidos!";
	}else{
				
		$url = "http://culturaz.santoandre.sp.gov.br/api/event/findByLocation";
		//$url = "http://culturaz.santoandre.sp.gov.br/api/event/find";
		
		$data = array(
			"@from" => exibirDataMysql($_POST['inicio']),
			"@to" => exibirDataMysql($_POST['final']),
			"@select" => "id, name, longDescription, terms, project, occurrences", 
			"@files" => "(header,avatar):url",
			"@order" => "id ASC"
			
			);
		
		$get_addr = $url.'?'.http_build_query($data);
		$ch = curl_init($get_addr);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$page = curl_exec($ch);
		
		$evento = json_decode($page);
		
		$ccsp = converterObjParaArray($evento);

        // gera a consulta
		header("Content-type: application/vnd.ms-word");
		header("Content-Disposition: attachment;Filename=revista.doc");

		?>
		
<html>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">
<body>
<style type='text/css'>
.style_01 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
</style>
		
		<?php 
		
		
		
		for($i = 0; $i < sizeof($evento); $i++){
		
					

		}

				
	}
}
?>


</body>
</html>



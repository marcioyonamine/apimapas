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
		
		$path = "download/";
		
		# create new zip object
		$zip = new ZipArchive();
		
		# create a temp file & open it
		$tmp_file = date('YmdHis')."_culturaz.zip";
		$zip->open($path.$tmp_file, ZipArchive::CREATE);
		
		for($i = 0; $i < sizeof($evento); $i++){
		
					

					if($ccsp[$i]['@files:header']['url'] ==  true){
									   # download file
						$download_file = file_get_contents($ccsp[$i]['@files:header']['url']);
						 #add it to the zip
						 $zip->addFromString(basename($ccsp[$i]['@files:header']['url']), $download_file);
					}
					if($ccsp[$i]['@files:avatar.avatarBig']['url'] ==  true){
									   # download file
						$download_file = file_get_contents($ccsp[$i]['@files:avatar.avatarBig']['url']);
						 #add it to the zip
						 $zip->addFromString(basename($ccsp[$i]['@files:avatar.avatar.Big']['url']), $download_file);
					}
		}

		$zip->close();
				
			}
		}
?>

<?php include "header.php"; ?>

<div class="container">
		<h1>Download de imagens do CulturAZ por período</h1>
    <div class="row">
    <p>Dependendo do número de imagens e tamanho, o processo pode levar mais tempo. </p>
        <div class='col-sm-6'>
            <div class="form-group">
            <form method="post" action="?">
                <label>Data Inicial</label>
                <div class='input-group date' class="datepicker">

                    <input type='text' class="form-control datepicker" name="inicio"/>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
                                <label>Data Final</label>
                <div class='input-group date' class="datepicker">

                    <input type='text' class="form-control datepicker" name="final" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
                       
            <div class="form-group">
            <br />
            <br />
                <input type="submit" class="btn-lg" name = "buscar" value="Gerar arquivo compactado">
                </div>
                </form>
            </div>
        </div>

    </div>
</div>

<div class="container">
    <div class="row">
        <div class='col-sm-6'>
            <div class="form-group">
           <?php 
		   if(isset($tmp_file)){ ?>
           <h1><a href="<?php echo $path.$tmp_file ?>" >Baixe o arquivo</a></h1>
			   
			 <?php }
		   ?>
               
            </div>
        </div>

    </div>
</div>
<?php
if(isset($_POST['buscar'])){
	echo "<pre>";
	var_dump($_POST);
	echo "</pre>";
	
	}

?>


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="assets/js/vendor/jquery.min.js"><\/script>')</script>
    <script src="js/bootstrap.min.js"></script>
    <script src="assets/js/docs.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="assets/js/ie10-viewport-bug-workaround.js"></script>
      <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script>
  $( function() {
    $( ".datepicker" ).datepicker(
	{
        dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
        dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
        dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
        monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
        monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
        dateFormat: 'dd/mm/yy',
        nextText: 'Próximo',
        prevText: 'Anterior'
    }
	);
  } );
  </script>
  </body>
</html>

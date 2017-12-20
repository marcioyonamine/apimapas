<?php 
//http://php.fnlist.com/php/json_decode
//http://freeonlinetools24.com/json-decode

include 'json_array.php';

//echo count($json_array['entity']['registrations']);
set_time_limit(0);
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
<?php 

/*
echo "<pre>";
var_dump($json_array['entity']['registrations']);
echo "</pre>";
*/
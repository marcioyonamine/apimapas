<?php 
$url = "http://culturaz.santoandre.sp.gov.br/api/event/findByLocation";
//$url = "http://culturaz.santoandre.sp.gov.br/api/event/find";



$data = array(
   	"@from" => "2017-10-01",
	"@to" => "2017-10-31",
	"@select" => "id, name, longDescription, terms, project, occurrences", 
	"@order" => "id ASC",
	"ownerId" => "EQ(1977)" 
	);

$get_addr = $url.'?'.http_build_query($data);
echo $get_addr;
?>
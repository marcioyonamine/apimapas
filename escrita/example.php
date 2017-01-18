<?php
require dirname(__DIR__) . '/vendor/autoload.php';

$mapas = new MapasSDK\MapasSDK(
    'http://spcultura.prefeitura.sp.gov.br/', 
    'chave01', 
    'chave02'
);

//

try{
  $newEvent = $mapas->createEntity('event', [
      'name' => 'Teste API Evento',
  	'shortDescription' => 'Teste Descricao',
  	'ownerId' => 413,
	'classificacaoEtaria' => 'Livre',
	'terms' => [
		'linguagem' => 'Teatro'
	]
  ]
  );
} catch(Exception $e) {
echo "<pre>";	
  var_dump($e->curl->response);
echo "</pre>";
}

//var_dump($newAgent);
//var_dump($newAgent->response);


//$agents = $mapas->apiGet('api/agent/find', [
//    '@select' => 'id,name,documento,emailPrivado',
//    'user' => 'EQ(@User:1)'
//]);
//
//var_dump($agents);

//
//print_r($mapas->getEntityDescription('agent'));
//print_r($mapas->getEntityTypes('space'));

//print_r($mapas->findEntity('agent', 83, 'id,name,location'));

//print_r($mapas->createEntity('agent', []));

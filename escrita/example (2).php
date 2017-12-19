<?php
//require dirname(__DIR__) . '/vendor/autoload.php';

require 'MapasSDK/MapasSDK.php';
require 'MapasSDK/Exceptions/BadRequest.php';
require 'MapasSDK/Exceptions/Exception.php';
require 'MapasSDK/Exceptions/Forbidden.php';
require 'MapasSDK/Exceptions/NotFound.php';
require 'MapasSDK/Exceptions/Unauthorized.php';
require 'MapasSDK/Exceptions/UnexpectedError.php';
require 'MapasSDK/Exceptions/ValidationError.php';


$mapas = new MapasSDK\MapasSDK(
    'http://culturaz.santoandre.sp.gov.br/', 
    '8HLRFPqZNOeRrKqIDJ1wg0CuEDx4didb', 
    'eYVN4liGaXKCaCK1s7DyCk0XuwP77HKBxDEzaPRidLjjQw6vQuGWztrCcioyFR11'
);

//

try{
  $newEvent = $mapas->createEntity('event', [
      'name' => 'Teste API Evento 2',
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

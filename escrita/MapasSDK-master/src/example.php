<?php
require dirname(__DIR__) . '/vendor/autoload.php';

$mapas = new MapasSDK\MapasSDK(
    'http://culturaz.santoandre.sp.gov.br/',
    '8HLRFPqZNOeRrKqIDJ1wg0CuEDx4didb',
    'eYVN4liGaXKCaCK1s7DyCk0XuwP77HKBxDEzaPRidLjjQw6vQuGWztrCcioyFR11',
    'HS256'
);

/*
try{
  $newEvent = $mapas->createEntity('event', [
      'name' => 'Teste API Evento',
  	'shortDescription' => 'Teste Descricao',
  	'ownerId' => 1977,
	'classificacaoEtaria' => 'Livre',
	//'status' => '',
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
*/
try{
$occurrence = $mapas->apiPost('registrations/find',[
    'project' => 258
]);


} catch(Exception $e) {
echo "<pre>";	
  var_dump($e->curl->response);
echo "</pre>";
}

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
/*
$space_id = 2;

$new_event = $mapas->createEntity('event', [
    'name' => 'Evento de teste ' . date('Y-m-d H:i:s'),
    'shortDescription' => 'evento criado pelo MapasSDK às ' . date('Y-m-d H:i:s'),
    'terms' => [
        'linguagem' => ['Cinema', 'Audiovisual'],
        'tag' => ['API', 'MapasSDK']
    ],
    'classificacaoEtaria' => '18 anos'
]);

// acontecendo uma única vez no dia 28 de Setembro de 2017 às 12:00 com duração de 120min e preço Gratuíto
$occurrence = $mapas->apiPost('eventOccurrence/create',[
    'eventId' => $new_event->id,
    'spaceId' => $space_id,
    'startsAt' => '12:00',
    'duration' => '120',
    // 'endsAt' => '14:00',
    'frequency' => 'once',
    'startsOn' => '2017-09-28',
    'until' => '',
    'description' => 'Dia 28 de setembro de 2017 às 12:00',
    'price' => 'Gratuito'
]);

// acontecendo diariamente de 1 a 30 de Setembro de 2017 às 15:00 com duração de 120min e preço Gratuíto
$occurrence = $mapas->apiPost('eventOccurrence/create',[
    'eventId' => $new_event->id,
    'spaceId' => $space_id,
    'startsAt' => '15:00',
    'duration' => '120',
    // 'endsAt' => '17:00',
    'frequency' => 'daily',
    'startsOn' => '2017-09-01',
    'until' => '2017-09-30',
    'description' => 'Diariamente de 1 a 30 de setembro de 2017 às 15:00',
    'price' => 'Gratuito'
]);

// acontecendo Toda seg, qua e sex de 1 a 30 de setembro de 2017 às 10:00
$occurrence = $mapas->apiPost('eventOccurrence/create',[
    'eventId' => $new_event->id,
    'spaceId' => $space_id,
    'startsAt' => '10:00',
    'duration' => '120',
    // 'endsAt' => '12:00',
    'frequency' => 'weekly',
    'startsOn' => '2017-09-01',
    'until' => '2017-09-30',
    'day' => [ // 0 = domingo, 1 = segunda, 2 = terça, 3 = quarta, 4 = quinta, 5 = sexta e 8 = sábado
        1 => 'on', // segunda
        3 => 'on', // quarta
        5 => 'on' // sexta
    ],
    'description' => 'Toda seg, qua e sex de 1 a 30 de setembro de 2017 às 10:00',
    'price' => 'Gratuito'
]);

*/
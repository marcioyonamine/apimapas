<?php

$space_id = 873;

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

?>
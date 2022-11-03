<?php
include __DIR__.'/../../event/EventService.php';

session_start();

header('Content-Type: application/json');
$output = array();


$eventService = new EventService();
$output['result'] = $eventService->getEvents();

echo json_encode($output['result']);
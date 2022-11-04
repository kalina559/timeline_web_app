<?php
include __DIR__.'/../BaseController.php';
include __DIR__.'/../../event/EventService.php';

class EventGetController extends BaseController
{
    function execute()
    {
        log_message(LogModes::Info->name, "Get events");

        session_start();

        header('Content-Type: application/json');
        $output = array();


        $eventService = new EventService();
        $output['result'] = $eventService->getEvents();

        $length =  count($output['result']);
        log_message(LogModes::Info->name, "Events length $length");

        echo json_encode($output['result']);
    }
}

$controller = new EventGetController();


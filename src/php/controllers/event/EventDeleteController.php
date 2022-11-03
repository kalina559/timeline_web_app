<?php
include __DIR__ . '/../BaseController.php';
include __DIR__ . '/../../event/EventService.php';

class EventDeleteController extends BaseController
{
    function execute()
    {
        session_start();

        header('Content-Type: application/json');
        $output = array();

        if (!isset($_POST['arguments'])) {
            $output['result'] = 'No arguments!';
        }

        $data = $_POST['arguments'];
        $eventService = new EventService();

        $id = $data['Id'];


        $eventService->deleteEvent($id);
        $output['result'] = 'success';

        echo json_encode($output['result']);
    }
}

$controller = new EventDeleteController(validateUserLoggedIn: true);

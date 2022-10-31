<?php
include '../common/CommonFunctions.php';
include 'EventHandler.php';

session_start();

header('Content-Type: application/json');
$aResult = array();

if (!isset($_POST['functionname'])) {
    $aResult['error'] = 'No function name!';
}

$data = $_POST['arguments'];

if (!isset($aResult['error'])) {

    switch ($_POST['functionname']) {
        case 'get':
            $aResult['result'] = EventHandler::getEvents();
            break;
        case 'add':
            $title = $data['Title'];
            $description = $data['Description'];
            $startDate = $data['StartDate'];
            $endDate = $data['EndDate'];
            $categoryId = $data['CategoryId'];
            $imageFile = $data['ImageFile'];

            if ($endDate != null && $startDate > $endDate) {
                $aResult['result'] = 'failed';
                break;
            }
            EventHandler::addEvent($title, $description, $startDate, $endDate, $categoryId, $imageFile);
            $aResult['result'] = 'success';
            break;
        case 'update':
            $id = $data['Id'];
            $title = $data['Title'];
            $description = $data['Description'];
            $startDate = $data['StartDate'];
            $endDate = $data['EndDate'];
            $categoryId = $data['CategoryId'];
            $imageFile = $data['ImageFile'];

            if ($endDate != null && $startDate > $endDate) {
                $aResult['result'] = 'failed';
                break;
            }

            EventHandler::editEvent($id, $title, $description, $startDate, $endDate, $categoryId, $imageFile);
            $aResult['result'] = 'success';
            break;

        case 'delete':
            $id = $data['Id'];            

            EventHandler::deleteEvent($id);
            $aResult['result'] = 'success';
            break;
        default:

            break;
    }
}
echo json_encode($aResult['result']);

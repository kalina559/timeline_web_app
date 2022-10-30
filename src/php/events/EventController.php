<?php
include '../common/CommonFunctions.php';
include '../account/AccountHandler.php';
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
            if(AccountHandler::validateUserLoggedIn()){
                $aResult['result'] = 'success';
            } else {
                $aResult['result'] = 'failed';
            }
            break;
        default:
            $aResult['result'] = 'failed';
            break;
    }
}
echo json_encode($aResult['result']);

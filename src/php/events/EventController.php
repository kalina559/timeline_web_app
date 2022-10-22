<?php
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
        case 'add':
            if(AccountHandler::checkIfUserLoggedIn()){
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
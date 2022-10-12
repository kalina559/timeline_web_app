<?php
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
            $aResult['result'] = 'success';
            break;
        default:
            $aResult['result'] = 'failed';
            break;
    }
}
echo json_encode($aResult['result']);

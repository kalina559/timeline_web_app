<?php
include 'AccountHandler.php';

session_start();

header('Content-Type: application/json');
$aResult = array();

if (!isset($_POST['functionname'])) {
    $aResult['error'] = 'No function name!';
}

$data = $_POST['arguments'];

if (!isset($aResult['error'])) {

    switch ($_POST['functionname']) {
        case 'login':
            $accountHandler = new AccountHandler;
            $login = $data['Login'];
            $pass = $data['Password'];

            $success = $accountHandler->tryLogin($login, $pass);
            if ($success !== TRUE) {
                $aResult['result'] = 'failed';
            } else {
                $aResult['result'] = 'success';
            }
            break;

        case 'logout':
            $aResult['result'] = 'success';
            
            break;
        default:
            $aResult['result'] = 'failed';
            break;
    }
}
echo json_encode($aResult['result']);

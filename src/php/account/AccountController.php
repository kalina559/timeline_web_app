<?php
include 'AccountHandler.php';


header('Content-Type: application/json');
$aResult = array();

if (!isset($_POST['functionname'])) {
    $aResult['error'] = 'No function name!';
}

if (!isset($aResult['error'])) {

    switch ($_POST['functionname']) {
        case 'login':
            $accountHandler = new AccountHandler;
            $login = 'kalj';
            $pass = 'kalj';

            $success = $accountHandler->tryLogin($login, $pass);
            if ($success !== TRUE) {
                $aResult['result'] = 'login failed';
            } else {
                $aResult['result'] = 'login succeeded';
            }
            break;

        default:
            $aResult['result'] = 'nope';
            break;
    }
}
echo json_encode($aResult['result']);

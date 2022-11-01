<?php
include '../common/CommonFunctions.php';
include 'AccountHandler.php';

session_start();

header('Content-Type: application/json');
$output = array();

if (!isset($_POST['functionname'])) {
    $output['error'] = 'No function name!';
}

$data = $_POST['arguments'];
log_message(LogModes::Info->name, "creating new object");

if (!isset($output['error'])) {

    switch ($_POST['functionname']) {
        case 'login':
            $login = $data['Login'];
            $pass = $data['Password'];

            $success =  AccountHandler::tryLogin($login, $pass);
            if ($success !== TRUE) {
                $output['result'] = 'failed';
            } else {
                $output['result'] = 'success';
            }
            break;

        case 'logout':
            $success =  AccountHandler::logout();
            $output['result'] = 'success';

            break;

        case 'checkIfLoggedIn':
            $result =  AccountHandler::getLoggedInUser();
            $output['result'] = $result;
            break;

        case 'updatePassword':
            $oldPassword = $data['OldPassword'];
            $newPassword = $data['NewPassword'];
            $result =  AccountHandler::updateUsersPassword($oldPassword, $newPassword);
            $output['result'] = $result;
            break;

        default:
            $output['result'] = 'failed';
            break;
    }
}
echo json_encode($output['result']);

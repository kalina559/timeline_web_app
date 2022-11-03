<?php
include __DIR__ . '/../BaseController.php';

class LoginController extends BaseController
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
        $accountService = new AccountService();

        $login = $data['Login'];
        $pass = $data['Password'];

        $success =  $accountService->login($login, $pass);
        if ($success !== TRUE) {
            $output['result'] = 'failed';
        } else {
            $output['result'] = 'success';
        }

        echo json_encode($output['result']);
    }
}

$controller = new LoginController();

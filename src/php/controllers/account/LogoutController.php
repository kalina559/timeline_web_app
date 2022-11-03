<?php
include __DIR__ . '/../BaseController.php';

class LogoutController extends BaseController
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

        $accountService->logout();
        $output['result'] = 'success';

        echo json_encode($output['result']);
    }
}

$controller = new LogoutController();

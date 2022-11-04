<?php
include '../BaseController.php';

class LoginStateController extends BaseController
{
    function execute()
    {
        session_start();

        header('Content-Type: application/json');
        $output = array();

        $accountService = new AccountService();

        $result =  $accountService->getLoggedInUser();
        $output['result'] = __DIR__;

        echo json_encode($output['result']);
    }
}

$controller = new LoginStateController();
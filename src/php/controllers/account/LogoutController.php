<?php
include __DIR__.'/../../account/AccountService.php';

session_start();

header('Content-Type: application/json');
$output = array();

if (!isset($_POST['arguments'])) {
    $output['result'] = 'No arguments!';
}

$data = $_POST['arguments'];
$accountService = new AccountService();

$success =  $accountService->logout();
$output['result'] = 'success';

echo json_encode($output['result']);

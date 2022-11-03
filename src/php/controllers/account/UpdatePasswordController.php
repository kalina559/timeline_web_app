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

$oldPassword = $data['OldPassword'];
$newPassword = $data['NewPassword'];
$result =  $accountService->updateUsersPassword($oldPassword, $newPassword);
$output['result'] = $result;

echo json_encode($output['result']);

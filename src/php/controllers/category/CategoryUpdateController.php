<?php
include __DIR__.'/../../categories/CategoriesService.php';

session_start();

header('Content-Type: application/json');
$output = array();

if (!isset($_POST['arguments'])) {
    $output['result'] = 'No arguments!';
}

$data = $_POST['arguments'];
$categoryService = new CategoriesService();

$id = $data['Id'];
$name = $data['Name'];
$colorHex = $data['ColorHex'];

if (!preg_match('/^#[a-f0-9]{6}$/i', $colorHex)) {
    $output['result'] = 'failed';
}
$categoryService->editCategory($id, $name, $colorHex);
$output['result'] = 'success';

echo json_encode($output['result']);

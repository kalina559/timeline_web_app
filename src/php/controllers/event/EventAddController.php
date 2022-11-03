<?php
include __DIR__.'/../../event/EventService.php';

session_start();

header('Content-Type: application/json');
$output = array();

if (!isset($_POST['arguments'])) {
    $output['result'] = 'No arguments!';
}

$data = $_POST['arguments'];
$eventService = new EventService();

$title = $data['Title'];
$description = $data['Description'];
$startDate = $data['StartDate'];
$endDate = $data['EndDate'];
$categoryId = $data['CategoryId'];
$imageFile = $data['ImageFile'];

if ($endDate != null && $startDate > $endDate) {
    $output['result'] = 'failed';
}

$eventService->addEvent($title, $description, $startDate, $endDate, $categoryId, $imageFile);
$output['result'] = 'success';

echo json_encode($output['result']);

<?php
include __DIR__.'/../../categories/CategoriesService.php';

session_start();

header('Content-Type: application/json');
$output = array();


$categoryService = new CategoriesService();
$output['result'] = $categoryService->getCategories();

echo json_encode($output['result']);
<?php
include '../BaseController.php';
include '../../categories/CategoriesService.php';

class CategoryGetController extends BaseController
{
    function execute()
    {
        session_start();

        header('Content-Type: application/json');
        $output = array();

        $categoryService = new CategoriesService();
        $output['result'] = $categoryService->getCategories();

        echo json_encode($output['result']);
    }
}

$controller = new CategoryGetController();

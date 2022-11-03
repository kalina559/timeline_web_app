<?php
include __DIR__ . '/../BaseController.php';
include __DIR__ . '/../../categories/CategoriesService.php';

class CategoryDeleteController extends BaseController
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
        $categoryService = new CategoriesService();

        $id = $data['Id'];

        $categoryService->deleteCategory($id);
        $output['result'] = 'success';

        echo json_encode($output['result']);
    }
}

$controller = new CategoryDeleteController(validateUserLoggedIn: true);
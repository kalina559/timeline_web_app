<?php
include __DIR__ . '/../BaseController.php';
include __DIR__ . '/../../categories/CategoriesService.php';

class CategoryUpdateController extends BaseController
{
    function execute()
    {
        $id = new InputField('Id');
        $name = new InputField('Name');
        $colorHex = new InputField('ColorHex');

        $categoryService = new CategoriesService();
        
        $categoryService->editCategory($id->get(), $name->get(), $colorHex->get());
    }
}

$controller = new CategoryUpdateController(requiresArguments: true, validateUserLoggedIn: true);

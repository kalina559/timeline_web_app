<?php
include '../common/CommonFunctions.php';
include '../account/AccountHandler.php';
include 'CategoriesHandler.php';

session_start();

header('Content-Type: application/json');
$aResult = array();

if (!isset($_POST['functionname'])) {
    $aResult['error'] = 'No function name!';
}

$data = $_POST['arguments'];

if (!isset($aResult['error'])) {

    switch ($_POST['functionname']) {
        case 'get':
            $aResult['result'] = CategoriesHandler::getCategories();
            break;
        case 'add':
            $name = $data['Name'];
            $colorHex = $data['ColorHex'];

            if (!preg_match('/^#[a-f0-9]{6}$/i', $colorHex)) {
                $aResult['result'] = 'failed';
                break;
            }

            CategoriesHandler::addCategory($name, $colorHex);
            $aResult['result'] = 'success';
            break;
        case 'update':
            $id = $data['Id'];
            $name = $data['Name'];
            $colorHex = $data['ColorHex'];

            if (!preg_match('/^#[a-f0-9]{6}$/i', $colorHex)) {
                $aResult['result'] = 'failed';
                break;
            }

            CategoriesHandler::editCategory($id, $name, $colorHex);
            $aResult['result'] = 'success';
            break;
        case 'delete':
            $id = $data['Id'];

            CategoriesHandler::deleteCategory($id);
            $aResult['result'] = 'success';
            break;
        default:
            $aResult['result'] = 'failed';
            break;
    }
}
echo json_encode($aResult['result']);

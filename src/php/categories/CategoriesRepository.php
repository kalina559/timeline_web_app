<?php
// include '../account/AccountService.php';
include __DIR__.'/../common/CommonFunctions.php';

class CategoriesRepository
{  
    private $con;
    //private $accountService;

    function __construct() {
        log_message(LogModes::Info->name, "Creating CategoriesRepository");
        $this->con = getDbConnection();
        //$this->accountService = new AccountService();
    }

    function __destruct() {
        log_message(LogModes::Info->name, "Deleting CategoriesRepository");
        $this->con->close();
        unset($this->con);
        unset($this->accountService);
    }

    public function getCategories()
    {

        $result = executeQuery(
            $this->con,
            "SELECT id, name, color_hex FROM categories"
        );

        $json = mysqli_fetch_all ($result, MYSQLI_ASSOC);


        return $json;
    }

    public function addCategory($name, $colorHex)
    {
        

        // if(!$this->accountService->validateUserLoggedIn()){
        //     throw new Exception("User is not logged in."); 
        // }

        executeQueryWithParams(
            $this->con,
            "INSERT INTO categories (name, color_hex) 
            VALUES (?,?)",
            'ss',
            $name, $colorHex
        );

    }

    public function editCategory($id, $name, $colorHex)
    {
        // if(!$this->accountService->validateUserLoggedIn()){
        //     throw new Exception("User is not logged in."); 
        // }


        executeQueryWithParams(
            $this->con,
            "UPDATE categories 
            SET name = ?, color_hex = ?
            WHERE id = ?",
            'sss',
            $name, $colorHex, $id
        );

    }

    public function deleteCategory($id)
    {
        // if(!AccountService::validateUserLoggedIn()){
        //     throw new Exception("User is not logged in."); 
        // }


        executeQueryWithParams(
            $this->con,
            "UPDATE events
            SET category_id = NULL
            WHERE category_id = ?",
            's',
            $id
        );

        executeQueryWithParams(
            $this->con,
            "DELETE FROM categories 
            WHERE id = ?",
            's',
            $id
        );

    }
}

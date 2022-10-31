<?php

class CategoriesHandler
{  
    static function getCategories()
    {
        $con = getDbConnection();

        $result = executeQuery(
            $con,
            "SELECT id, name, color_hex FROM categories"
        );

        $json = mysqli_fetch_all ($result, MYSQLI_ASSOC);

        $con->close();

        return $json;
    }

    static function addCategory($name, $colorHex)
    {
        if(!AccountHandler::validateUserLoggedIn()){
            throw new Exception("User is not logged in."); 
        }

        $con = getDbConnection();

        executeQueryWithParams(
            $con,
            "INSERT INTO categories (name, color_hex) 
            VALUES (?,?)",
            'ss',
            $name, $colorHex
        );

        $con->close();
    }

    static function editCategory($id, $name, $colorHex)
    {
        if(!AccountHandler::validateUserLoggedIn()){
            throw new Exception("User is not logged in."); 
        }

        $con = getDbConnection();

        executeQueryWithParams(
            $con,
            "UPDATE categories 
            SET name = ?, color_hex = ?
            WHERE id = ?",
            'sss',
            $name, $colorHex, $id
        );

        $con->close();
    }

    static function deleteCategory($id)
    {
        if(!AccountHandler::validateUserLoggedIn()){
            throw new Exception("User is not logged in."); 
        }

        $con = getDbConnection();

        executeQueryWithParams(
            $con,
            "UPDATE events
            SET category_id = NULL
            WHERE category_id = ?",
            's',
            $id
        );

        executeQueryWithParams(
            $con,
            "DELETE FROM categories 
            WHERE id = ?",
            's',
            $id
        );

        $con->close();
    }
}

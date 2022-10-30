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
}

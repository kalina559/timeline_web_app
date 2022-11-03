<?php
include_once __DIR__.'/../BaseRepository.php';

class CategoriesRepository extends BaseRepository
{  
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

<?php
include '../account/AccountHandler.php';

class EventHandler
{  
    static function getEvents()
    {
        $con = getDbConnection();

        $result = executeQuery(
            $con,
            "SELECT id, start_date, end_date, category_id, title, description FROM events"
        );

        $json = mysqli_fetch_all ($result, MYSQLI_ASSOC);

        $con->close();

        return $json;
    }

    static function addEvent($title, $description, $startDate, $endDate, $categoryId, $imageFile)
    {
        if(!AccountHandler::validateUserLoggedIn()){
            throw new Exception("User is not logged in."); 
        }

        $con = getDbConnection();

        executeQueryWithParams(
            $con,
            "INSERT INTO events (title, description, start_date, end_date, category_id) 
            VALUES (?,?,?,?,?)",
            'sssss',
            $title, $description, $startDate, $endDate != null ? $endDate : NULL, $categoryId
        );

        $con->close();
    }
}

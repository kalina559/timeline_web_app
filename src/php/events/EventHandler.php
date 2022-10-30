<?php

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
}

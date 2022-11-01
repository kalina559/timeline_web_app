<?php
include '../account/AccountHandler.php';

class EventHandler
{
    static function getEvents()
    {
        $con = getDbConnection();

        $result = executeQuery(
            $con,
            "SELECT events.id, start_date, end_date, category_id, title, description, NULL as base64String
            FROM events
            ORDER BY start_date DESC"
        );

        //$json = mysqli_fetch_all($result, MYSQLI_ASSOC);

        $json = [];
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $row['base64String'] = 'xd';
            array_push($json, $row);
        }

        $con->close();

        return $json;
    }

    static function addEvent($title, $description, $startDate, $endDate, $categoryId, $imageFile)
    {
        if (!AccountHandler::validateUserLoggedIn()) {
            throw new Exception("User is not logged in.");
        }

        $con = getDbConnection();

        executeQueryWithParams(
            $con,
            "INSERT INTO events (title, description, start_date, end_date, category_id) 
            VALUES (?,?,?,?,?)",
            'sssss',
            $title,
            $description,
            $startDate,
            $endDate != null ? $endDate : NULL,
            $categoryId
        );


        // TODO extract method
        $data = explode(',', $imageFile);
        $filename_path = "event$con->insert_id.jpg";
        $decoded = base64_decode($data[1]);
        file_put_contents("../../../images/" . $filename_path, $decoded);

        $con->close();
    }

    static function editEvent($id, $title, $description, $startDate, $endDate, $categoryId, $imageFile)
    {
        if (!AccountHandler::validateUserLoggedIn()) {
            throw new Exception("User is not logged in.");
        }

        $con = getDbConnection();

        executeQueryWithParams(
            $con,
            "UPDATE events 
            SET title = ?, description = ?, start_date = ?, end_date = ?, category_id = ?
            WHERE id = ?",
            'ssssss',
            $title,
            $description,
            $startDate,
            $endDate != null ? $endDate : NULL,
            $categoryId,
            $id
        );

        $con->close();
    }

    static function deleteEvent($id)
    {
        if (!AccountHandler::validateUserLoggedIn()) {
            throw new Exception("User is not logged in.");
        }

        $con = getDbConnection();

        executeQueryWithParams(
            $con,
            "DELETE FROM events 
            WHERE id = ?",
            's',
            $id
        );

        $con->close();
    }

    static function base64_to_jpeg($base64_string, $output_file)
    {
        // open the output file for writing
        $ifp = fopen($output_file, 'wb');

        // split the string on commas
        // $data[ 0 ] == "data:image/png;base64"
        // $data[ 1 ] == <actual base64 string>
        $data = explode(',', $base64_string);

        // we could add validation here with ensuring count( $data ) > 1
        fwrite($ifp, base64_decode($data[1]));

        // clean up the file resource
        fclose($ifp);

        return $output_file;
    }
}

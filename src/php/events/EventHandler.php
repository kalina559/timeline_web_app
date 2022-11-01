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


        $json = [];
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $currentEventId = $row['id'];

            $path = "../../../images/event$currentEventId.jpg";
            if (file_exists($path)) {
                $data = file_get_contents($path);
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                $row['base64String'] = $base64;
            }

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

        // add new image file
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

        // remove the existing image file
        if (file_exists("../../../images/event$id.jpg")) {
            // TODO check if the data is equal
            unlink("../../../images/event$id.jpg");
        }

        $scaledImage = EventHandler::resizeImage($imageFile, 200);

        $filename_path = "../../../images/event$id.jpg";
        imagejpeg($scaledImage, $filename_path);

        $con->close();
    }

    static function resizeImage($imageData, $newHeight){
        $data = explode(',', $imageData);
        $decodedSource = base64_decode($data[1]);

        $im = imagecreatefromstring($decodedSource);
        $source_width = imagesx($im);
        $source_height = imagesy($im);
        $ratio =  $source_width / $source_height;

        $new_height = $newHeight;
        $new_width = $ratio * $newHeight;

        return imagescale($im, $new_width, $new_height);
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

        // remove the existing image file
        if (file_exists("../../../images/event$id.jpg")) {
            // TODO check if the data is equal
            unlink("../../../images/event$id.jpg");
        }

        $con->close();
    }

    static function addEventImage($id)
    {
    }

    static function removeEventImage($id)
    {
    }

    static function replaceEventImage($id)
    {
    }
}

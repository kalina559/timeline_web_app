<?php
include_once __DIR__.'/../BaseRepository.php';

class EventRepository extends BaseRepository
{
    public function getEvents()
    {
        $result = executeQuery(
            $this->con,
            "SELECT events.id, start_date, end_date, category_id, title, description, NULL as base64String
            FROM events
            ORDER BY start_date DESC"
        );


        $json = [];
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $currentEventId = $row['id'];

            $path = __DIR__."/../../../images/event$currentEventId.jpg";
            if (file_exists($path)) {
                $data = file_get_contents($path);
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                $row['base64String'] = $base64;
            }

            array_push($json, $row);
        }

        return $json;
    }

    public function addEvent($title, $description, $startDate, $endDate, $categoryId, $imageFile)
    {
        executeQueryWithParams(
            $this->con,
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
        $eventId = $this->con->insert_id;
        $filename_path = "event$eventId.jpg";

        $decoded = base64_decode($data[1]);
        file_put_contents(__DIR__."/../../../images/" . $filename_path, $decoded);

    }

    public function editEvent($id, $title, $description, $startDate, $endDate, $categoryId, $imageFile)
    {
        executeQueryWithParams(
            $this->con,
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
        if (file_exists(__DIR__."/../../../images/event$id.jpg")) {
            // TODO check if the data is equal
            unlink(__DIR__."/../../../images/event$id.jpg");
        }

        $scaledImage = $this->resizeImage($imageFile, 200);

        $filename_path = __DIR__."/../../../images/event$id.jpg";
        imagejpeg($scaledImage, $filename_path);
    }

    public function resizeImage($imageData, $newHeight){
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

    public function deleteEvent($id)
    {
        executeQueryWithParams(
            $this->con,
            "DELETE FROM events 
            WHERE id = ?",
            's',
            $id
        );

        // remove the existing image file
        if (file_exists(__DIR__."/../../../images/event$id.jpg")) {
            // TODO check if the data is equal
            unlink(__DIR__."/../../../images/event$id.jpg");
        }
    }
}

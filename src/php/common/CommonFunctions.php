<?php

enum LogModes
{
    case Debug;
    case Info;
    case Error;
}

function log_message($type, $message) {
    $now = new DateTime();
    $timestamp = $now->format('Y-m-d H:i:s');
    $date = $now->format('Y-m-d');
    
    $fileDir = dirname(__FILE__);
    $logMessage = "$timestamp $type $message $fileDir".PHP_EOL;

    file_put_contents("$fileDir/../logs/log_$date.log", $logMessage, FILE_APPEND);

}

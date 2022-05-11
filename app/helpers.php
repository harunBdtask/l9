<?php
// Translated the language phrases
if (!function_exists('get_phrases')) {
    function get_phrases($text = [])
    {
        $filePath = public_path() . '/assets/language/english.json';
        $jsonString = openJsonFile($filePath); //Json file to Array
        if (!empty($text)) {
            $key = implode('_', $text);
            if (array_key_exists($key, $jsonString)) {
            } else {
                $jsonString[$key] = ucfirst(str_replace('_', ' ', $key));
                $jsonData = json_encode($jsonString, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                file_put_contents($filePath, stripslashes($jsonData));
            }
        }
        return ucwords($jsonString[$key]);
    }
}

if (!function_exists('openJsonFile')) {
    function openJsonFile($filePath)
    {
        $jsonString = [];
        if (file_exists($filePath)) {
            $jsonString = file_get_contents($filePath);
            $jsonString = json_decode($jsonString, true);
        }
        return $jsonString;
    }
}

if (!function_exists('saveJsonFile')) {
    function saveJsonFile($filePath, $updating_key, $updating_value)
    {
        $jsonString = [];
        if (file_exists($filePath)) {
            $jsonString = file_get_contents($filePath);
            $jsonString = json_decode($jsonString, true);
            $jsonString[$updating_key] = $updating_value;
        } else {
            $jsonString[$updating_key] = $updating_value;
        }
        $jsonData = json_encode($jsonString, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents($filePath, stripslashes($jsonData));
    }
}

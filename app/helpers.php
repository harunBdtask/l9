<?php
// Translated the language phrases
if(!function_exists('get_phrases')) {
     
    function get_phrases($text = [])
    {
        return ucwords(implode(" ", $text));
    }
 
}
<?php

if (!function_exists('constantSort')) {
    function constantSort($items = [])
    {
        $data =[['id'=>'','text'=>'select']];
        foreach($items as $key=>$item)
        {
            $data[] = ['id' => $key, 'text' => $item];
        }
        return $data;
    }
}
if (!function_exists('constantSort2')) {
    function constantSort2($items = [])
    {
        $data =[['id'=>'','text'=>'select']];
        foreach($items as $key=>$item)
        {
            $data[] = ['id' => $item, 'text' => $item];
        }
        return $data;
    }
}
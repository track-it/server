<?php

namespace Trackit\Support;


use Illuminate\Support\Collection;

class JsonResponse
{
    var $items = [];
    var $error = [];
 
    public static function success($items)
    {
        $jsonResponse = new JsonResponse();


        if (is_array($items) || $items instanceof Collection) {
            $jsonResponse->items = $items;
        } else {
            array_push($jsonResponse->items, $items);
        }

        return $jsonResponse;
    }

    public static function failure($error)
    {
        $jsonResponse = new JsonResponse();

        $jsonResponse->error = $error;

        return $jsonResponse;
    }

    public function __toString()
    {
        return json_encode($this);
    }
}
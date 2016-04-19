<?php

namespace Trackit\Support;

class JsonResponse
{
    var $items = [];
    public function __construct($items)
    {
        $this->items = $items;
    }

    public function __toString()
    {
        return json_encode($this);
    }
}
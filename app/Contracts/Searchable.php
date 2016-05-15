<?php

namespace Trackit\Contracts;

interface Searchable
{
    static function search($query, $statuses);
}

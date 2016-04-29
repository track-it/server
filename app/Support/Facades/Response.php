<?php

namespace Trackit\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Illuminate\Contracts\Routing\ResponseFactory
 */
class Response extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Trackit\Support\TrackitResponseFactory';
    }
}

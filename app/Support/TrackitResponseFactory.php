<?php

namespace Trackit\Support;

use Illuminate\Support\Collection;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\JsonSerializable;
use Illuminate\Database\Eloquent\Model;

class TrackitResponseFactory extends ResponseFactory
{
    public function json($data = [], $status = 200, array $headers = [], $options = 0)
    {
        $formattedData = $data;

        if ($data instanceof Model || $data instanceof Collection) {
            $formattedData = [
                'data' => $data,
            ];
        }

        return parent::json($formattedData, $status, $headers, $options);
    }
}
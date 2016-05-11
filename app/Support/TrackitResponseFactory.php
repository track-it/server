<?php

namespace Trackit\Support;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Routing\JsonSerializable;
use Illuminate\Contracts\Support\Arrayable;

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

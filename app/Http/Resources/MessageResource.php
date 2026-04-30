<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'status' => $this->resource['status'] ?? 200,
            'message' => $this->resource['message'],
            'data' => $this->resource['data'] ?? null,
        ];
    }
}

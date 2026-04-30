<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ErrorResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'status' => $this->resource['status'] ?? 500,
            'message' => $this->resource['message'] ?? 'Something went wrong.',
            'data' => $this->resource['data'] ?? null,
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'status' => $this->resource['status'] ?? 200,
            'message' => $this->resource['message'] ?? 'Something went wrong.',
            'data' => $this->resource['data'] ?? null,
        ];
    }

    public function withResponse(Request $request, JsonResponse $response): void
    {
        $response->setStatusCode($this->resource['status'] ?? 200);
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderCollection extends ResourceCollection
{
    public $collects = OrderResourceItem::class;

    public function toArray(Request $request): array
    {
        return [
            'status' => 200,
            'message' => 'Orders fetched successfully.',
            'data' => [
                'items' => $this->collection,
                'pagination' => [
                    'current_page' => $this->resource->currentPage(),
                    'per_page' => $this->resource->perPage(),
                    'total' => $this->resource->total(),
                    'last_page' => $this->resource->lastPage(),
                ],
            ],
        ];
    }

    public function withResponse(Request $request, JsonResponse $response): void
    {
        $data = $response->getData(true);
        unset($data['links'], $data['meta']);
        $response->setData($data);
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResourceItem extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'total_price' => $this->total_price,
            'order_status' => $this->status->value,
            'created_at' => $this->created_at?->toDateTimeString(),
            'items' => OrderItemResourceItem::collection(
                $this->whenLoaded('items')
            ),
        ];
    }
}

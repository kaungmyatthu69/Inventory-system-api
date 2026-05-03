<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatus;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\MessageResource;
use App\Http\Resources\OrderCollection;
use App\Http\Resources\OrderResource;
use App\Services\OrderService;

class OrderController extends Controller
{
    public function __construct(public OrderService $orderService) {}

    public function index(): OrderCollection
    {
        return new OrderCollection(
            $this->orderService->list(request()->only('status'))
        );
    }

    public function store(StoreOrderRequest $request): MessageResource
    {
        $order = $this->orderService->create($request->validated('items'));

        return new MessageResource([
            'status' => HttpStatus::CREATED->value,
            'message' => 'Order created successfully.',
            'data' => new OrderResource($order),
        ]);
    }

    public function show(string $id): MessageResource
    {
        $order = $this->orderService->findOrFail($id);

        return new MessageResource([
            'status' => HttpStatus::OK->value,
            'message' => 'Order fetched successfully.',
            'data' => new OrderResource($order),
        ]);
    }
}

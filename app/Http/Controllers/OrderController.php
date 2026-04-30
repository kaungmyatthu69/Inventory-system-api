<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\MessageResource;
use App\Http\Resources\OrderCollection;
use App\Http\Resources\OrderResourceItem;
use App\Models\Order;
use App\Services\OrderService;

class OrderController extends Controller
{
    public function __construct(public OrderService $orderService) {}

    public function index(): OrderCollection
    {
        return new OrderCollection(
            $this->orderService->list()
        );
    }

    public function store(StoreOrderRequest $request): MessageResource
    {
        $order = $this->orderService->create($request->validated('items'));

        return new MessageResource([
            'status' => 201,
            'message' => 'Order created successfully.',
            'data' => new OrderResourceItem($order),
        ]);
    }

    public function show(string $id): MessageResource
    {
        $order = $this->findOrderOrRespond($id);

        if ($order instanceof MessageResource) {
            return $order;
        }

        return new MessageResource([
            'status' => 200,
            'message' => 'Order fetched successfully.',
            'data' => new OrderResourceItem($order),
        ]);
    }

    private function findOrderOrRespond(string $id): Order|MessageResource
    {
        $order = $this->orderService->find($id, auth()->id());

        if (! $order) {
            return new MessageResource([
                'status' => 404,
                'message' => 'Order not found.',
                'data' => null,
            ]);
        }

        return $order;
    }
}

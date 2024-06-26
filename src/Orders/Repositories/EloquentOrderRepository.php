<?php

namespace EcomDemo\Orders\Repositories;

use EcomDemo\Orders\Entities\Order;
use EcomDemo\Orders\Repositories\Contracts\OrderRepository;
use EcomDemo\PaginationFilters;
use EcomDemo\Users\Entities\User;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentOrderRepository implements OrderRepository
{
    /**
     * @inheritDoc
     */
    public function getUserOrders(User $user, PaginationFilters $filters): LengthAwarePaginator
    {
        return Order::with('payment')
            ->where('user_id', $user->getKey())
            ->orderBy($filters->getSortBy(), $filters->getSortDirection())
            ->paginate($filters->getPage(), $filters->getLimit());
    }
}

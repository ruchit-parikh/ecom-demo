<?php

namespace EcomDemo\Orders\Repositories\Contracts;

use EcomDemo\PaginationFilters;
use EcomDemo\Users\Entities\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface OrderRepository
{
    /**
     * @param User $user
     * @param PaginationFilters $filters
     *
     * @return LengthAwarePaginator
     */
    public function getUserOrders(User $user, PaginationFilters $filters): LengthAwarePaginator;
}

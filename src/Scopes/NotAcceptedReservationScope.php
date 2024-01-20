<?php

namespace App\Scopes;

use Cycle\ORM\Select;

class NotAcceptedReservationScope implements Select\ScopeInterface
{
    public function apply(Select\QueryBuilder $query): void
    {
        $query->where('status', '=', 3);
    }
}

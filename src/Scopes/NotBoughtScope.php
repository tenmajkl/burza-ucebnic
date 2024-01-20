<?php

namespace App\Scopes;

use Cycle\ORM\Select;

class NotBoughtScope implements Select\ScopeInterface
{
    public function apply(Select\QueryBuilder $query): void
    {
        $query->where('bought_at', '=', null);
    }
}

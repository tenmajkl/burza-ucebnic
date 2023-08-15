<?php

namespace App\Entities;

use Cycle\Database\Query\SelectQuery;
use Cycle\ORM\Select;

enum OfferSort: string
{
    case Optimal = 'optimal';
    case Newest = 'newest';
    case Oldest = 'oldest';
    case Cheapest = 'cheapest';
    case MostExpensive = 'most-expensive';
    case BestReviews = 'best-reviews';
    case WorstReviews = 'worst-reviews';
    
    public function sort(Select $select): Select
    {
        // TODO incorporate ratings 
        return match ($this) {
            self::Newest => $select->orderBy('created_at', SelectQuery::SORT_DESC),
            self::Oldest => $select->orderBy('created_at', SelectQuery::SORT_ASC),
            self::Cheapest => $select->orderBy('price', SelectQuery::SORT_ASC),
            self::MostExpensive => $select->orderBy('price', SelectQuery::SORT_ASC),
            self::BestReviews => $select, //->orderBy('user.received_ratings', SelectQuery::SORT_DESC),
            self::WorstReviews => $select, //->orderBy('user.received_ratings', SelectQuery::SORT_ASC),
            self::Optimal => $select
                ->orderBy('created_at', SelectQuery::SORT_DESC)
                ->orderBy('price', SelectQuery::SORT_ASC),
//                ->orderBy('user.received_ratings', SelectQuery::SORT_DESC),
        };
    }
}

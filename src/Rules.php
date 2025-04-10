<?php

declare(strict_types=1);

namespace App;

use App\Contracts\ORM;
use App\Entities\BookState;
use App\Entities\OfferSort;
use Lemon\Kernel\Application;
use Lemon\Support\CaseConverter;

/**
 * Custom validation rules.
 */
class Rules
{
    public function __construct(
        private Application $app
    ) {
        $this->app->get('validation')->rules()
            ->rule('id', [$this, 'id'])
            ->rule('state', [$this, 'state'])
            ->rule('state-get', [$this, 'stateGet'])
            ->rule('offer-state', [$this, 'offerState'])
            ->rule('sort', [$this, 'sort'])
        ;
    }

    public function id(string $id, string $entity): bool
    {
        if (!is_numeric($id)) {
            return false;
        }

        $id = (int) $id;

        /** @var ORM $db */
        $db = $this->app->get(ORM::class);

        return !is_null(
            $db->getORM()
                ->getRepository('App\Entities\\'.CaseConverter::toPascal($entity))
                ->findByPK($id)
        );
    }

    public function state(string $state): bool
    {
        return BookState::fromId((int) $state) ? true : false;
    }

    /**
     * For situations where there is state any (mostly reading situations).
     */
    public function stateGet(string $state): bool
    {
        return '-1' === $state || BookState::fromId((int) $state) ? true : false;
    }

    public function offerState(string $state): bool
    {
        return in_array($state, ['0', '1']);
    }

    public function sort(string $sort): bool
    {
        return in_array(OfferSort::from($sort), OfferSort::cases());
    }
}

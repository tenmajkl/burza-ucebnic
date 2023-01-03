<?php

namespace App\Contracts;

use App\Entities\Offer;
use App\Entities\User;

interface Auth
{
    public function user(): User;

    public function authorizeOfferEditation(Offer $offer): bool;
}

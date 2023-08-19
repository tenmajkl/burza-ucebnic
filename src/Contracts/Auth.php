<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Entities\Offer;
use App\Entities\User;

interface Auth
{
    public function user(): ?User;

    public function canChangeForgottenPassword(string $token): bool;
    
    public function canEditOffer(Offer $offer): bool;
}

<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Entities\Offer;
use App\Entities\User;

interface Discord
{
    public function sendWebhook(array $message): bool;

    public function sendIssue(string $description, User $author): bool;

    public function sendRequest(string $email, string $school): bool;

    public function sendOffer(Offer $offer): bool;

    public function sendSuccess(): bool;
}

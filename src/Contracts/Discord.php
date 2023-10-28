<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Entities\User;

interface Discord
{
    public function sendWebhook(array $message): bool;

    public function sendIssue(string $description, User $author): bool;
}

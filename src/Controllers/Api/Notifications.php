<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Contracts\Auth;
use App\Contracts\Notifier;
use App\Entities\OfferNotification;
use App\Contracts\ORM;

class Notifications
{
    public function index(Notifier $notifier, Auth $auth): array
    {
        return [
            'status' => 200,
            'message' => 'OK',
            'data' => $notifier->of($auth->user()),
        ];
    }

    public function update(?OfferNotification $target, Notifier $notifier)
    {
        if (null === $target) {
            return error(404);
        }

        $notifier->see($target);

        return [
            'status' => 200,
            'message' => 'OK',
        ];
    }

    public function clear(Auth $auth, ORM $orm)
    {
        
    }

    public function readAll()
    {

    }
}

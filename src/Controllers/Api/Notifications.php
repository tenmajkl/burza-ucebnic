<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Contracts\Auth;
use App\Contracts\Notifier;
use App\Contracts\ORM;
use App\Entities\Notification;

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

    public function update(?Notification $target, Notifier $notifier)
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

    public function getUnread(Auth $auth, ORM $orm): array
    {
        $notifications = $orm->getORM()->getRepository(Notification::class)
            ->select()
            ->where('user.id', $auth->user()->id)
            ->where('seen', '0')
            ->count()
        ;

        return [
            'status' => 200,
            'message' => 'OK',
            'data' => $notifications,
        ];
    }

    public function clear(Notifier $notifier, Auth $auth, ORM $orm)
    {
        $orm->db()->table('notifications')
            ->delete([
                'user_id' => $auth->user()->id,
                'seen' => 1,
            ])->run()
        ;

        return $this->index($notifier, $auth);
    }

    public function readAll(Notifier $notifier, Auth $auth, ORM $orm)
    {
        $orm->db()->table('notifications')
            ->update([
                'seen' => 1,
            ])->where([
                          'user_id' => $auth->user()->id,
                      ])->run()
        ;

        return $this->index($notifier, $auth);
    }
}

<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Contracts\Auth;
use App\Contracts\Notifier;
use App\Contracts\ORM;
use App\Entities\Message;
use App\Entities\Reservation;
use App\Entities\ReservationState;
use DateTimeImmutable;
use Lemon\Http\Request;
use Lemon\Terminal;
use Lemon\Validator;

class Messages
{
    public function show(?Reservation $target)
    {
        if (!$target) {
            return error(404);
        }

        return [
            'code' => 200,
            'message' => 'OK',
            'data' => $target->messages,
            'session_id' => session_id(), // IDK IF THIS IS SAFE?
        ];
    }

    public function update(?Reservation $target, Request $request, Auth $auth, ORM $orm, Notifier $notifier)
    {
        $request->validate([
            'content' => 'max:512|min:1',
            'notify' => 'numeric|gte:0|lte:1',
        ], fn () => response([
            'code' => 400,
            'message' => Validator::error(),
        ])->code(400));

        if (!$target) {
            return error(404);
        }

        if (ReservationState::Active !== $target->status) {
            return error(404);
        }

        $message = new Message($request->get('content'), $auth->user(), $target);
        $orm->getEntityManager()->persist($message)->run();
        $message->createdAt = new DateTimeImmutable('now');

        if ($request->get('notify') != 0) {
            $user = $auth->user()->id === $target->offer->user->id ? $target->user : $target->offer->user;
            $notifier->notifyNewMessage($user, $target->offer);
        }

        return [
            'code' => 200,
            'message' => 'OK',
            'data' => $message->jsonSerialize(),
        ];
    }
}

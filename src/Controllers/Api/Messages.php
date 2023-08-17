<?php

namespace App\Controllers\Api;

use App\Contracts\Auth;
use App\Entities\Message;
use App\Entities\Offer;
use App\Entities\Reservation;
use Lemon\Http\Request;
use Lemon\Validator;
use App\Contracts\ORM;

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
        ];
    }

    public function update(?Reservation $target, Request $request, Auth $auth, ORM $orm)
    {
        $request->validate([
            'content' => 'max:512|min:1',
        ], fn() => response([
            'code' => 400,
            'message' => Validator::error(),
        ])->code(400));

        if (!$target) {
            return error(404);
        }

        $message = new Message($request->get('content'), $auth->user(), $target);
        $orm->getEntityManager()->persist($message)->run();

        return [
            'code' => 200,
            'message' => 'OK',
        ];
    }
}

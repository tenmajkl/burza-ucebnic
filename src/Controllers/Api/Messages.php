<?php

namespace App\Controllers\Api;

use App\Contracts\Auth;
use App\Entities\Message;
use App\Entities\Offer;
use App\Entities\Reservation;
use Lemon\Http\Request;
use Lemon\Validator;

class Messages
{
    public function show(?Reservation $target)
    {
        if (!$target) {
            return error(404);
        }

        return $target->messages;
    }

    public function update(?Reservation $target, Request $request, Auth $auth)
    {
        $request->validate([
            'content' => 'max:512|min:1',
        ], response([
            'code' => 400,
            'message' => Validator::error(),
        ])->code(400));

        if (!$target) {
            return error(404);
        }

        $message = new Message($request->get('content'), $auth->user(), $target);

        return [
            'code' => 200,
            'message' => 'OK',
        ];
    }
}

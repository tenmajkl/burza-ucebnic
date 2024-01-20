<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Contracts\Auth;
use App\Contracts\ORM;
use App\Entities\Offer;
use App\Entities\Rating as RatingEntity;
use App\Entities\Reservation;
use App\Entities\ReservationState;
use Lemon\Http\Request;
use Lemon\Http\Response;
use Lemon\Validator;

class Rating
{
    public function verify(?Offer $target, Auth $auth)
    {
        return [
            'status' => 200,
            'message' => 'OK',
            'data' => $target->canRate($auth->user()),
        ];
    }

    public function update(?Reservation $target, ORM $orm, Auth $auth, Request $request): Response|array
    {
        if (null === $target) {
            return error(404);
        }

        if (!$target->canRate($auth->user())) {
            // bez sance brasko
            return error(404);
        }

        $request->validate([
            'rating' => 'regex:-?1',
        ], fn () => response([
            'status' => 400,
            'message' => Validator::error(),
        ]));

        $user = $offer->user;
        $user->rating += $request->get('rating'); 

        $orm->getEntityManager()->persist($user)->run();

        if ($target->status === ReservationState::Accepted) {
            $orm->getEntityManager()->delete($target)->run();
        }

        $offer->rated = true;

        return [
            'status' => 200,
            'message' => 'OK',
        ];
    }
}

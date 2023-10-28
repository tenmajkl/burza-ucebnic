<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Contracts\Auth;
use App\Contracts\ORM;
use App\Entities\Offer;
use App\Entities\Rating as RatingEntity;
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

    public function update(?Offer $target, ORM $orm, Auth $auth, Request $request): Response|array
    {
        if (null === $target) {
            return error(404);
        }

        if ($target->canRate($auth->user())) {
            // bez sance brasko
            return error(404);
        }

        $request->validate([
            'rating' => 'regex:-?1',
        ], fn () => response([
            'status' => 400,
            'message' => Validator::error(),
        ]));

        $rating = new RatingEntity((int) $request->get('rating'), $auth->user(), $target);

        $orm->getEntityManager()->persist($rating)->run();

        return [
            'status' => 200,
            'message' => 'OK',
        ];
    }
}

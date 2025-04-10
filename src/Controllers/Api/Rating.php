<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Contracts\Auth;
use App\Contracts\ORM;
use App\Entities\Notification;
use App\Entities\Offer;
use App\Entities\RatingAbility;
use Lemon\Http\Request;
use Lemon\Http\Response;
use Lemon\Validator;

class Rating
{
    public function verify(?Offer $target, Auth $auth): array
    {
        return [
            'status' => 200,
            'message' => 'OK',
            'data' => null !== $target,
        ];
    }

    public function update(?RatingAbility $target, ORM $orm, Auth $auth, Request $request): array|Response
    {
        if (null === $target) {
            return error(404);
        }

        if ($target->user !== $auth->user()) {
            return error(404);
        }

        $request->validate([
            'rating' => 'regex:-?1',
        ], fn () => response([
            'status' => 400,
            'message' => Validator::error(),
        ]));

        $user = $target->rated;
        $user->rating += (int) $request->get('rating');

        $notification = $orm->getORM()->getRepository(Notification::class)->findOne([
            'rating.id' => $target->id,
        ]);

        $orm->getEntityManager()->delete($target)->delete($notification)->persist($user)->run();

        return [
            'status' => 200,
            'message' => 'OK',
        ];
    }
}

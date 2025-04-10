<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Contracts\Auth;
use App\Contracts\ORM;
use App\Entities\Year;
use Lemon\Contracts\Http\Session;
use Lemon\Http\Request;
use Lemon\Validator;

class Account
{
    public function getInfo(Auth $auth): array
    {
        $u = $auth->user();

        return [
            'code' => 200,
            'data' => [
                'name' => $u->email,
                'year' => $u->year->name,
                'school' => $u->year->school->name,
                'rating' => $u->rating,
            ],
        ];
    }

    public function delete(Auth $auth, ORM $orm, Session $session, Request $request)
    {
        $request->validate([
            'password' => 'min:8|max:256',
        ], fn () => response([
            'code' => 400,
            'message' => Validator::error(),
        ])->code(400));

        if (!password_verify($request->get('password'), $auth->user()->password)) {
            return response([
                'code' => 400,
                'message' => text('auth.wrong-password'),
            ])->code(400);
        }

        $orm->getEntityManager()->delete($auth->user())->run();

        $session->clear();

        return [
            'code' => 200,
            'status' => 'OK',
        ];
    }

    public function getYears(Auth $auth)
    {
        $user = $auth->user();

        return [
            'code' => 200,
            'data' => array_filter(
                $user->year->school->years,
                fn (Year $year) => $year->visible && $year->id !== $user->year->id
            ),
        ];
    }

    public function changeYear(Auth $auth, Request $request, ORM $orm)
    {
        $request->validate([
            'password' => 'min:8|max:256',
            'year' => 'numeric',
        ], fn () => response([
            'code' => 400,
            'message' => Validator::error(),
        ])->code(400));

        $user = $auth->user();
        $year = $orm->getORM()->getRepository(Year::class)->findOne([
            'id' => $request->get('year'),
            'school.id' => $user->year->school->id,
            'visible' => true,
        ]);

        if (!$year) {
            return error(400);
        }

        if (!password_verify($request->get('password'), $user->password)) {
            return response([
                'code' => 400,
                'message' => text('auth.wrong-password'),
            ])->code(400);
        }

        $user->year = $year;
        $orm->getEntityManager()->persist($user)->run();

        return [
            'code' => 200,
            'message' => 'OK',
        ];
    }
}

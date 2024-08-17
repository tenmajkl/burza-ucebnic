<?php

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
            'name' => $u->email(),
            'year' => $u->year->name,
            'school' => $u->year->school->name,
            'rating' => $u->rating,
        ];
    } 

    public function delete(Auth $auth, ORM $orm, Session $session, Request $request)
    {
        $request->validate([
            'password' => 'min:8|max:256',
        ], fn() => [
            'code' => 400,
            'message' => Validator::error()
        ]);

        if (!password_verify($request->get('password'), $auth->user()->password)) {
            return [
                'code' => 400,
                'message' => text('wrong-password'),
            ];
        }

        $orm->getEntityManager()->delete($auth->user());

        $session->clear();

        return redirect('/');
    }

    public function getYears(Auth $auth) 
    {
        return [
            'code' => 200,
            'data' => $auth->user()->year->school->years,
        ];
    }

    public function changeYear(?Year $target, Auth $auth, Request $request)
    {
        if ($target === null || $target->school->id === $auth->user()->year->school->id) {
            return error(404);
        }

        $request->validate([
            'password' => 'min:8|max:256',
        ], fn() => [
            'code' => 400,
            'message' => Validator::error()
        ]);

        if (!password_verify($request->get('password'), $auth->user()->password)) {
            return [
                'code' => 400,
                'message' => text('wrong-password'),
            ];
        }

        $auth->user()->year = $target;

        return [
            'code' => 200, 
            'message' => 'OK',
        ];
    }
}   

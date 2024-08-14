<?php

namespace App\Controllers\Api;

use App\Contracts\Auth;
use App\Contracts\ORM;
use App\Entities\Year;

class Account
{

    public function getInfo(): array
    {
        
    } 

    public function delete(Auth $auth, ORM $orm)
    {
        $orm->getEntityManager()->delete($auth->user());

        return [
            'code' => 200, 
            'message' => 'OK',
        ];
    }

    public function changeYear(?Year $target, Auth $auth)
    {
        if ($target === null || $target->school->id === $auth->user()->year->school->id) {
            return error(404);
        }

        $auth->user()->year = $target;

        return [
            'code' => 200, 
            'message' => 'OK',
        ];
    }
}

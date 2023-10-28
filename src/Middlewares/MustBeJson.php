<?php

declare(strict_types=1);

namespace App\Middlewares;

use Lemon\Http\Request;

class MustBeJson
{
    public function handle(Request $request)
    {
        if ('POST' === $request->method && !$request->is('application/json')) {
            return response([
                'status' => '400',
                'message' => 'The request must be json',
            ])->code(400);
        }
    }
}

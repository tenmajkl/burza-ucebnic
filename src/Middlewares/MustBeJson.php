<?php

namespace App\Middlewares;

use Lemon\Http\Request;

class MustBeJson
{
    public function handle(Request $request)
    {
        if ($request->method === 'POST' && !$request->is('application/json')) {
            return response([
                'status' => '400',
                'message' => 'The request must be json',
            ])->code(400);
        }
    }
}

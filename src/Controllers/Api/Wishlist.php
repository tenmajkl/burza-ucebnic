<?php

namespace App\Controllers\Api;

use App\Contracts\Auth;
use App\Contracts\ORM;
use App\Entities\Book;
use App\Entities\Inquiry;
use Lemon\Http\Request;

class Wishlist
{
    public function index(ORM $orm, Auth $auth): array
    {
        return [
            'code' => 200,
            'message' => 'OK',
            'data' => [
                $orm->getORM()->getRepository(Inquiry::class)->findAll([
                    'user.id' => $auth->user()->id,
                ]),
                $orm->getORM()->getRepository(Book::class)->findAll([
                    'subjects.year.id' => $auth->user()->year->id,
    //                'inquiries.user.id' => ['!=' => $auth->user()->id], TBD
                ]),
            ],
        ];
    }

    public function update(Book $target, Auth $auth, Request $request, ORM $orm): array
    {
        $request->validate([
            'max_price' => 'numeric|greaterThan:0|smallerThan:1000',
        ], response([ 
            'code' => 400,
            'message' => 'Bad Request',
        ])->code(400));

        $inquiry = new Inquiry(
            $target,
            $auth->user(),
            $request->get('max_price'),
        );

        $orm->getEntityManager()->persist($inquiry);

        return $this->index($orm, $auth);
    }

    public function delete(Inquiry $target, ORM $orm, Auth $auth): array
    {
        $orm->getEntityManager()->delete($target);

        return $this->index($orm, $auth);
    }
}

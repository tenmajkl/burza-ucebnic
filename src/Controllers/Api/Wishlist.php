<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Contracts\Auth;
use App\Contracts\ORM;
use App\Entities\Book;
use App\Entities\Inquiry;
use Lemon\Http\Request;
use Lemon\Http\Response;

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
                    // TODO there is probably way to filter inquired books, but time is money and we need both
                ]),
            ],
        ];
    }

    public function update(?Book $target, Auth $auth, Request $request, ORM $orm): array|Response
    {
        if (null === $target) {
            return error(404);
        }

        $request->validate([
            'max_price' => 'numeric|gt:0|lt:1000',
        ], response([
            'code' => 400,
            'message' => 'Bad Request',
        ])->code(400));

        $inquiry = new Inquiry(
            $target,
            $auth->user(),
            $request->get('max_price'),
        );

        $orm->getEntityManager()->persist($inquiry)->run();

        return $this->index($orm, $auth);
    }

    public function delete(?Inquiry $target, ORM $orm, Auth $auth): array
    {
        if (null === $target) {
            return error(404);
        }

        $orm->getEntityManager()->delete($target)->run();

        return $this->index($orm, $auth);
    }
}

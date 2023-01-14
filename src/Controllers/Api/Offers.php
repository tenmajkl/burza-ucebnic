<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Contracts\Auth;
use App\Contracts\ORM;
use App\Entities\Book;
use App\Entities\Offer;
use App\Entities\Subject;
use Lemon\Contracts\Validation\Validator;
use Lemon\Http\Request;
use Lemon\Http\Response;

class Offers
{
    public function all(ORM $orm, Validator $validator, Request $request, Auth $auth): array|Response
    {
        $ok = $validator->validate($request->query(), [
            'year' => 'numeric|max:3',
            'subject' => 'numeric|max:3',
            'sort' => 'numeric|max:3',
        ]);
        
        $book = $orm->getORM()->getRepository(Book::class)
                              ->select()
                              ->load('subject')
                              ->wherePK((int) $request->query('subject'))
                              ->load('year')
                              ->wherePK((int) $request->query('year'))
                              ->fetchOne();

        if ($book->schoool->id !== $auth->user()->school->id) {
            return response([
                'status' => '401',
                'message' => 'Unauthorized',
            ]);
        }

        if (null === $book || !$ok) {
            return response([
                'status' => '400',
                'message' => 'Bad data',
            ])->code(400);
        }

        return $orm->getORM()->getRepository(Offer::class)
                             ->select()
                             ->load('book')
                             ->wherePK($book->id)
                             ->fetchOne() ?? [];
    }
}

<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Contracts\Auth;
use App\Contracts\ORM;
use App\Entities\Book;
use App\Entities\Offer;
use App\Entities\Subject;
use App\Entities\Year;
use Lemon\Http\Request;
use Lemon\Http\Response;
use Lemon\Kernel\Application;
use Lemon\Validator;

class Offers
{
    public function create(ORM $orm, Auth $auth): array
    {
        $years = $orm->getORM()->getRepository(Year::class)->findAll(['school.id' => $auth->user()->year->school->id, 'id' => ['!=' => $auth->user()->year->id]]);
        $states = Offer::States;

        return [
            'years' => $years,
            'states' => $states,
        ];
    }

    public function store(Application $app, ORM $orm, Auth $auth, Request $request): array|Response
    {
        $request->validate([
            'book' => 'numeric',
            'price' => 'numeric',
            'state' => 'state',
        ], fn() => response([
            'status' => '400',
            'message' => Validator::error(),
        ])->code(400));

        if ($orm->getORM()->getRepository(Offer::class)->findOne(['book.id' => $request->get('book'), 'author.id' => $auth->user()->id])) {
            return response([
                'status' => '400',
                'message' => text('validation.already-offered'),
            ])->code(400);
        }

        $school = $auth->user()->year->school;
        $book = $orm->getORM()->getRepository(Book::class)
                              ->findOne(['id' => $request->get('book'), 'subjects.year.school.id' => $school->id]);

        if (null === $book) {
            return response([
                'status' => '400',
                'message' => text('validation.unknown-book'),
            ])->code(400);
        }

        if (($image = $request->get('image')) === null) {
            return response([
                'status' => '400',
                'message' => text('validation.missing-image'),
            ])->code(400);
        }

        $type = explode('data:', explode(';', explode(',', $image)[0])[0])[1];

        if (!in_array($type, ['image/png', 'image/jpeg', 'image/jpg'])) {
            return response([
                'status' => '400',
                'message' => text('validation.invalid-image-type'),
            ])->code(400);
        }

        if (strlen($image) > 1024 * 1024 * 2) {
            return response([
                'status' => '400',
                'message' => text('validation.image-too-big'),
            ])->code(400);
        }

        $offer = new Offer(
            $book,
            $request->get('price'),
            $request->get('state'),
            $auth->user(),
        );

        $orm->getEntityManager()->persist($offer)->run();

        file_put_contents($app->file('storage.images.offers.'.$offer->id, 'image'), $image);
        
        return [
            'status' => '200',
            'message' => text('validation.ok'),
        ];
    }
}

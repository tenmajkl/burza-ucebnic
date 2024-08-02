<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Contracts\Auth;
use App\Contracts\Notifier;
use App\Contracts\ORM;
use App\Entities\Book;
use App\Entities\BookState;
use App\Entities\Offer;
use App\Entities\OfferSort;
use App\Entities\Report;
use App\Entities\Year;
use Lemon\Http\Request;
use Lemon\Http\Response;
use Lemon\Kernel\Application;
use Lemon\Validator;

class Offers
{
    public function index(Request $request, ORM $orm, Auth $auth)
    {
        $ok = Validator::validate($request->query() ?? [], [
            'subject' => 'numeric',
            'state' => 'state-get',
            'sort' => 'sort',
        ]);

        if (!$ok) {
            return response([
                'status' => '400',
                'message' => Validator::error(),
            ])->code(400);
        }

        $subject = $request->query('subject');
        $state = $request->query('state');
        $sort = $request->query('sort');

        $state = (int) $state;


        $select = $orm->getORM()->getRepository(Offer::class)
            ->select()                
            ->where(['bought_at' => null])
            ->where(['book.subjects.id' => $subject])
            ->where(['book.subjects.year.id' => $auth->user()->year->id])
            ->where(-1 === $state ? [] : ['state' => BookState::fromId($state)])
        ;

        $select = OfferSort::from($sort)->sort($select);

        $data = $select->fetchAll();

        $user = $auth->user();
        foreach ($data as $offer) {
            $offer->canUserMakeReservation($user);
        }

        return $data;
    }

    public function init(Auth $auth)
    {
        return [
            'subjects' => $auth->user()->year->subjects,
            'states' => BookState::all(),
            'sorts' => OfferSort::cases(),
        ];
    }

    public function create(ORM $orm, Auth $auth): array
    {
        $years = $orm->getORM()->getRepository(Year::class)->findAll(['school.id' => $auth->user()->year->school->id, 'visible' => true]);
        $states = BookState::allCreate();

        return [
            'years' => $years,
            'states' => $states,
        ];
    }

    public function store(Application $app, ORM $orm, Auth $auth, Request $request, Notifier $notifier): array|Response
    {
        $request->validate([
            'book' => 'numeric',
            'price' => 'numeric|min:0',
            'state' => 'state',
        ], fn () => response([
            'status' => '400',
            'message' => Validator::error(),
        ])->code(400));

        if ($orm->getORM()->getRepository(Offer::class)->findOne(['book.id' => $request->get('book'), 'user.id' => $auth->user()->id])) {
            return response([
                'status' => '400',
                'message' => text('validation.already-offered'),
            ])->code(400);
        }

        $school = $auth->user()->year->school;
        $book = $orm->getORM()->getRepository(Book::class)
            ->findOne(['id' => $request->get('book'), 'subjects.year.school.id' => $school->id])
        ;

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
            (int) $request->get('price'),
            BookState::fromId((int) $request->get('state')),
            $auth->user(),
        );

        $orm->getEntityManager()->persist($offer)->run();

        file_put_contents($app->file('storage.images.offers.'.$offer->id, 'image'), $image);

        foreach ($book->inquiries as $inquiry) {
            if ($offer->price <= $inquiry->expected_price) {
                $notifier->notifyWishlist($inquiry->user, $offer);
            }
        }

        return [
            'status' => '200',
            'message' => text('validation.ok'),
        ];
    }

    /**
     * Returns offers of logged user.
     */
    public function mine(Auth $auth)
    {
        return [
            'status' => '200',
            'message' => 'OK',
            'data' => $auth->user()->offers,
        ];
    }

    public function update(?Offer $target, Request $request, ORM $orm, Auth $auth)
    {
        if (!$target || $target->user->id !== $auth->user()->id) {
            return error(404);
        }

        $request->validate([
            'price' => 'numeric|gt:0|lt:1000',
        ], fn () => response([
            'status' => '400',
            'message' => Validator::error(),
        ])->code(400));

        $target->price = (int) $request->get('price');

        $orm->getEntityManager()->persist($target)->run();

        return [
            'status' => '200',
            'message' => 'OK',
        ];
    }

    public function delete(?Offer $target, Auth $auth, ORM $orm)
    {
        if (!$target || $target->user->id !== $auth->user()->id) {
            return error(404);
        }

        $orm->getEntityManager()->delete($target)->run();

        return [
            'status' => '200',
            'message' => 'OK',
        ];
    }

    public function report(?Offer $target, Auth $auth, ORM $orm, Request $request) 
    {
        $request->validate([
            'reason' => 'min:8|max:2048',
        ], fn() => error(400));

        if (!$target) {
            return error(404);
        }

        $report = new Report($request->get('reason'), $auth->user(), $target);
        
        $orm->getEntityManager()->persist($report)->run();

        return [
            'status' => '200',
            'message' => 'OK',
        ];
    }
}

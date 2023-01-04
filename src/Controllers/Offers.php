<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Contracts\Auth;
use App\Contracts\ORM;
use App\Entities\Book;
use App\Entities\Offer;
use Lemon\Http\Request;
use Lemon\Http\Response;
use Lemon\Templating\Template;

class Offers
{
    public function index(Auth $auth): Template
    {
        return template('offers.index', offers: $auth->user()->offers);
    }

    public function create(ORM $orm): Template
    {
        $books = $orm->getORM()->getRepository(Book::class)->findAll();
        return template('offers.create', books: $books);
    }

    public function store(Request $request, Auth $auth, ORM $orm): Template
    {
        // TODO uploading images, have no idea how to do it lol
        $ok = $request->validate([
            'book' => 'id:book',
            'price' => 'numeric',
            'description' => 'max:256',
        ]);

        if (!$ok) {
            return template('offers.create', message: 'validation-error');
        }

        $book = $orm->getORM()->getRepository(Book::class)->findByPK($request->get('id'));

        $offer = new Offer($book, (int) $request->get('price'), $request->get('description'), $auth->user());

        $orm->getEntityManager()->persist($offer);

        return template('offers.index', message: 'create_success', offers: $auth->user()->offers);
    }

    public function show($target, Auth $auth, ORM $orm): Template
    {
        $offer = $orm->getORM()->getRepository(Offer::class)->findByPK($target);
        $edit = $auth->user()->id === $offer->author->id;

        return template('offers.show', edit: $edit, offer: $offer);
    }

    public function update($target, Auth $auth, ORM $orm, Request $request): Template|Response
    {
        $offer = $orm->getORM()->getRepository(Offer::class)->findByPK($target);
        if (!$auth->authorizeOfferEditation($offer)) {
            return error(403);
        }

        $ok = $request->validate([
            'book' => 'book',
            'price' => 'numeric',
            'description' => 'max:256',
        ]);

        if (!$ok) {
            return template('offers.show', edit: true, offer: $offer, message: 'validation_error');
        }

        $offer->price = $request->get('price');
        $offer->description = $request->get('description');

        $orm->getEntityManager()->persist($offer);

        return template('offers.show', edit: true, offer: $offer, message: 'edit_success');
    }

    public function destroy($target, ORM $orm, Auth $auth): Response
    {
        $offer = $orm->getORM()->getRepository(Offer::class)->findByPK($target);
        if (!$auth->authorizeOfferEditation($offer)) {
            return error(403);
        }

        $orm->getEntityManager()->delete($offer);

        return redirect('offers');
    }
}

<?php

namespace App\Controllers;

use App\Contracts\Auth;
use App\Contracts\ORM;
use App\Entities\Book;
use App\Entities\Offer;
use Lemon\Http\Request;
use Lemon\Http\Responses\RedirectResponse;
use Lemon\Templating\Template;

class Offers
{
    public function index(Auth $auth): Template
    {
        return template('offers.index', offers: $auth->user()->offers);
    }

    public function create(): Template
    {
        return template('offers.create');
    }

    public function store(Request $request, Auth $auth, ORM $orm): Template
    {
        // TODO uploading images, have no idea how to do it lol
        $ok = $request->validate([
            'book' => 'book',
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

    public function show($offer, Auth $auth, ORM $orm): Template
    {
        $offer = $orm->getORM()->getRepository(Offer::class)->findByPK($offer);
        $edit = $auth->user()->id === $offer->author->id;
        return template('offers.show', edit: $edit, offer: $offer);
    }

    public function update($offer, Auth $auth, ORM $orm, Request $request): Template
    {
        $offer = $orm->getORM()->getRepository(Offer::class)->findByPK($offer);
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

    public function destroy($offer, ORM $orm, Auth $auth): RedirectResponse
    {
        $offer = $orm->getORM()->getRepository(Offer::class)->findByPK($offer);
        if (!$auth->authorizeOfferEditation($offer)) {
            return error(403);
        }

        $orm->getEntityManager()->delete($offer);
        
        return redirect('offers');
    }
}

<?php

namespace App;

use App\Contracts\Auth as AuthContract;
use App\Entities\Offer;
use App\Entities\User;
use Lemon\Contracts\Http\Session;

class Auth implements AuthContract
{
    public function __construct(
        private Session $session,
        private ORM $orm
    ) {

    }

    public function user(): User
    {
        return $this->orm->getORM()->getRepository(User::class)->findOne([
            'email' => $this->session->get('email')
        ]);
    }

    public function authorizeOfferEditation(Offer $offer): bool
    {
        return $this->user()->id === $offer->author->id;
    }
}

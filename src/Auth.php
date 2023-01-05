<?php

declare(strict_types=1);

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
            'email' => $this->session->get('email'),
        ]);
    }

    public function canChangeForgottenPassword(string $token): bool
    {
        return $this->session->get('token') == $token;
    }

    public function canEditOffer(Offer $offer): bool
    {
        return $this->user()->id === $offer->author->id;
    }
}

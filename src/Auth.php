<?php

declare(strict_types=1);

namespace App;

use App\Contracts\Auth as AuthContract;
use App\Entities\Offer;
use App\Entities\User;
use Lemon\Contracts\Http\Session;

class Auth implements AuthContract
{
    public const Roles = [
        0 => 'user',
        1 => 'admin',
    ];

    public function __construct(
        private Session $session,
        private ORM $orm
    ) {}

    public function user(): ?User
    {
        if (!$this->session->has('email')) {
            return null;
        }

        return $this->orm->getORM()->getRepository(User::class)->findOne([
            'email' => $this->session->get('email'),
            'email_host' => $this->session->get('host'),
            'year.school.id' => $this->session->get('school'),
        ]);
    }

    public function canChangeForgottenPassword(string $token): bool
    {
        return
            $this->session->has('token')
            && $this->session->get('token') == $token;
    }

    public function canEditOffer(Offer $offer): bool
    {
        return $this->user()->id === $offer->user->id;
    }
}

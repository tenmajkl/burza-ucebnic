<?php

namespace App;

use App\Contracts\ORM;
use App\Entities\Book;
use Lemon\Config\Exceptions\ConfigException;
use Lemon\Kernel\Application;


/**
 * Custom validation rules
 */
class Rules
{
    public function __construct(
        Application $app
    ) {
        $app->get('validation')->rules()
            ->rule('school_email', [$this, 'schoolEmail'])
            ->rule('book', [$this, 'book'])
        ;
    }

    public function schoolEmail(string $email): bool
    {
        return str_ends_with($email, (env('EMAIL') ?? throw new ConfigException('Undefined env variable EMAIL')));
    }

    public function book(string $book): bool
    {
        if (!is_numeric($book)) {
            return $false;
        }

        $book = (int) $book;

        /** @var ORM $db */
        $db = $this->app->get(ORM::class);
        return !is_null(
                    $db->getORM()
                       ->getRepository(Book::class)
                       ->findByPK($book)
               );
    }
}

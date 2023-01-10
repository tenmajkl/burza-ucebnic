<?php

declare(strict_types=1);

namespace App;

use App\Contracts\ORM;
use App\Entities\School;
use Lemon\Config\Exceptions\ConfigException;
use Lemon\Kernel\Application;
use Lemon\Support\CaseConverter;

/**
 * Custom validation rules.
 */
class Rules
{
    public function __construct(
        private Application $app
    ) {
        $this->app->get('validation')->rules()
            ->rule('school_email', [$this, 'schoolEmail'])
            ->rule('id', [$this, 'id'])
        ;
    }

    public function schoolEmail(string $email): bool
    {
        [$email, $host] = explode('@', $email);
        $school = $this->app->get(ORM::class)->getORM()->getRepository(School::class)->findOne(['email' => $host]);

        return $school !== null;
    }

    public function id(string $id, string $entity): bool
    {
        if (!is_numeric($id)) {
            return false;
        }

        $id = (int) $id;

        /** @var ORM $db */
        $db = $this->app->get(ORM::class);

        return !is_null(
            $db->getORM()
                ->getRepository('App\\Entities\\'.CaseConverter::toPascal($entity))
                ->findByPK($id)
        );
    }
}

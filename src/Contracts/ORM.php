<?php

namespace App\Contracts;

use Cycle\ORM\EntityManagerInterface;
use Cycle\ORM\ORMInterface;

interface ORM
{
    public function getORM(): ORMInterface;

    public function getEntityManager(): EntityManagerInterface;
}

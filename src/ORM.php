<?php

declare(strict_types=1);

namespace App;

use App\Contracts\ORM as ORMContract;
use Cycle\Annotated;
use Cycle\Database\Config\DatabaseConfig;
use Cycle\Database\DatabaseInterface;
use Cycle\Database\DatabaseManager;
use Cycle\ORM\EntityManager;
use Cycle\ORM\EntityManagerInterface;
use Cycle\ORM\Factory;
use Cycle\ORM\ORM as CycleORM;
use Cycle\ORM\ORMInterface;
use Cycle\ORM\Schema as ORMSchema;
use Cycle\Schema;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Lemon\Contracts\Config\Config;
use Lemon\Contracts\Support\Env;
use Spiral\Tokenizer\ClassLocator;
use Symfony\Component\Finder\Finder;

class ORM implements ORMContract
{
    public readonly DatabaseManager $dbal;

    public readonly EntityManagerInterface $manager;

    private readonly ORMInterface $orm;

    public function __construct(
        public readonly Config $config,
        Env $env
    ) {
        // Maybe consider some bootloader class
        $this->dbal = new DatabaseManager(new DatabaseConfig($this->config->get('database')));

        $finder = (new Finder())->files()->in([__DIR__]);
        $locator = new ClassLocator($finder);

        AnnotationRegistry::registerLoader('class_exists');

        $settings = [
            new Schema\Generator\ResetTables(),
            new Annotated\Embeddings($locator),
            new Annotated\Entities($locator),
            new Annotated\TableInheritance(),
            new Annotated\MergeColumns(),
            new Schema\Generator\GenerateRelations(),
            new Schema\Generator\GenerateModifiers(),
            new Schema\Generator\ValidateEntities(),
            new Schema\Generator\RenderTables(),
            new Schema\Generator\RenderRelations(),
            new Schema\Generator\RenderModifiers(),
            new Annotated\MergeIndexes(),
            new Schema\Generator\GenerateTypecast(),
        ];

        if ($env->get('DEBUG', false)) {
            $settings[] = new Schema\Generator\SyncTables();
        }

        $schema = (new Schema\Compiler())->compile(new Schema\Registry($this->dbal), $settings);

        $this->orm = new CycleORM(new Factory($this->dbal), new ORMSchema($schema));
        $this->manager = new EntityManager($this->orm);
    }

    public function getORM(): ORMInterface
    {
        return $this->orm;
    }

    public function getEntityManager(): EntityManagerInterface
    {
        return $this->manager;
    }

    public function db(): DatabaseInterface
    {
        return $this->dbal->database();
    }
}

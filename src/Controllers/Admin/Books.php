<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Contracts\Auth;
use App\Contracts\ORM;
use App\Entities\Book;
use App\Entities\Subject;
use App\Entities\Year;
use Lemon\Http\Request;
use Lemon\Kernel\Application;

class Books
{
    public function index(ORM $orm, Auth $auth)
    {
        $books = $orm->getORM()->getRepository(Book::class)
            ->select()
            ->where('subjects.year.school.id', $auth->user()->year->school->id)
            ->fetchAll()
        ;

        return template('admin.books.index', books: $books);
    }

    public function create(ORM $orm, Auth $auth)
    {
        $subjects = $orm->getORM()->getRepository(Subject::class)
            ->select()
            ->where('year.school.id', $auth->user()->year->school->id)
            ->fetchAll()
        ;

        return template('admin.books.create', subjects: $subjects);
    }

    public function store(ORM $orm, Auth $auth, Request $request, Application $app)
    {
        $request->validate([
            'name' => 'max:512',
            'author' => 'max:512',
            'release_year' => 'numeric',
            'publisher' => 'max:512',
        ], $this->create($orm, $auth));

        if ($request->get('release_year') < 0 && $request->get('release_year') > (int) date('Y')) {
            return $this->create($orm, $auth);
        }

        $book = new Book(
            $request->get('name'),
            $request->get('author'),
            $request->get('release_year'),
            $request->get('publisher'),
        );

        $years = [];

        // we support max 16 subjects
        for ($index = 1; $index <= 16; ++$index) {
            if (($id = $request->get("subject{$index}")) === null) {
                break;
            }

            $subject = $orm->getORM()->getRepository(Subject::class)->findOne(['id' => $id, 'year.school.id' => $auth->user()->year->school->id]);
            $year = $subject->year->id;
            if (isset($years[$year])) {
                return $this->create($orm, $auth); // TODO errors
            }
            $years[$year] = true;
            $book->subjects[$id] = $subject;
        }

        unset($years); // jsem ted zas nejakou dobu programoval jen v c

        $book->subjects = array_values($book->subjects);

        if (!$request->hasFile('cover')) {
            return $this->create($orm, $auth);
        }

        if (UPLOAD_ERR_OK !== $request->file('cover')->error) {
            return $this->create($orm, $auth);
        }

        if ($request->file('cover')->size > 1024 * 1024 * 2) {
            return $this->create($orm, $auth);
        }

        if ('image/png' !== $request->file('cover')->type) {
            return $this->create($orm, $auth);
        }

        $orm->getEntityManager()->persist($book)->run();

        $request->file('cover')->copy($app->file('public.img.cover.'.$book->id, 'png'));

        return redirect('/admin/books');
    }

    public function show(ORM $orm, Auth $auth, ?Book $target)
    {
        if (null === $target) {
            return error(404);
        }

        $subjects = $orm->getORM()->getRepository(Subject::class)
            ->select()
            ->where('year.school.id', $auth->user()->year->school->id)
            ->fetchAll()
        ;

        return template('admin.books.show', book: $target, subjects: $subjects);
    }

    public function update(ORM $orm, Auth $auth, Request $request, ?Book $target)
    {
        $request->validate([
            'name' => 'max:512',
            'author' => 'max:512',
            'release_year' => 'numeric',
            'publisher' => 'max:512',
        ], $this->show($orm, $auth, $target));

        if ($request->get('release_year') < 0 && $request->get('release_year') > (int) date('Y')) {
            return $this->create($orm, $auth);
        }

        if (null === $target) {
            return error(404);
        }

        $target->name = $request->get('name');
        $target->author = $request->get('author');
        $target->release_year = $request->get('release_year');
        $target->publisher = $request->get('publisher');

        $subjects = [];

        // we support max 16 subjects
        for ($index = 1; $index <= 16; ++$index) {
            if (($id = $request->get("subject{$index}")) === null) {
                break;
            }

            $subjects[$id] = $orm->getORM()->getRepository(Subject::class)->findOne(['id' => $id, 'year.school.id' => $auth->user()->year->school->id]);
        }

        $target->subjects = array_values($subjects);

        $orm->getEntityManager()->persist($target)->run();

        return redirect('/admin/books');
    }

    public function delete(ORM $orm, Auth $auth, ?Book $target)
    {
        if (null === $target) {
            return error(404);
        }

        $target->subjects = [];

        $orm->getEntityManager()->delete($target)->run();

        return redirect('/admin/books');
    }

    public function uploadMenu()
    {
        return template('admin.books.upload');
    }

    public function upload(Request $request, ORM $orm, Auth $auth)
    {
        if (UPLOAD_ERR_OK !== $request->file('books')?->error) {
            return $this->uploadMenu();
        }

        if ('text/csv' !== $request->file('books')->type) {
            return $this->uploadMenu();
        }

        /**
         * THE FORMAT.
         *
         * subject,year,name,author,publisher,release_year
         *
         * which will be converted to this shit db architecture
         *
         * probably ineffitient as shit
         */
        $file = fopen($request->file('books')->tmp_path, 'r');

        $school = $auth->user()->year->school;

        while ($line = fgetcsv($file)) {
            [$subject_name, $year_name, $name, $author, $publisher, $release_year] = $line;

            if (($year = $orm->getORM()->getRepository(Year::class)->findOne(['name' => $year_name, 'school.id' => $school->id])) === null) {
                $year = new Year($year_name, $school);
                $orm->getEntityManager()->persist($year)->run();
            }

            if (($subject = $orm->getORM()->getRepository(Subject::class)->findOne(['name' => $subject_name, 'year.id' => $year->id])) === null) {
                $subject = new Subject($subject_name);
                $subject->year = $year;
                $orm->getEntityManager()->persist($subject)->run();
            }

            if ($book = $orm->getORM()->getRepository(Book::class)->findOne(['name' => $name, 'author' => $author, 'publisher' => $publisher, 'release_year' => $release_year])) {
                $book->subjects[] = $subject;
                $orm->getEntityManager()->persist($book)->run();

                continue;
            }

            $book = new Book($name, $author, (int) $release_year, $publisher);
            $book->subjects[] = $subject;

            $orm->getEntityManager()->persist($book)->run();
        }

        fclose($file);

        return redirect('/admin/books');
    }
}

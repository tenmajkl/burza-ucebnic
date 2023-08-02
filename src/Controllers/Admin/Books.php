<?php

namespace App\Controllers\Admin;

use App\Contracts\Auth;
use App\Contracts\ORM;
use App\Entities\Book;
use App\Entities\Subject;
use Lemon\Http\Request;
use Lemon\Kernel\Application;

class Books
{
    public function index(ORM $orm, Auth $auth)
    {
        $books = $orm->getORM()->getRepository(Book::class)
                               ->select()
                               ->where('subjects.year.school.id', $auth->user()->year->school->id)
                               ->fetchAll();

        return template('admin.books.index', books: $books);
    }

    public function create(ORM $orm, Auth $auth)
    {
        $subjects = $orm->getORM()->getRepository(Subject::class)
                                  ->select()
                                  ->where('year.school.id', $auth->user()->year->school->id)
                                  ->fetchAll();

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
        
        // we support max 16 subjects
        for ($index = 1; $index <= 16; $index++) {
            if (($id = $request->get("subject{$index}")) === null) {
                break;
            }

            $book->subjects[$id] = $orm->getORM()->getRepository(Subject::class)->findOne(['id' => $id, 'year.school.id' => $auth->user()->year->school->id]);
        }

        $book->subjects = array_values($book->subjects);

        if (!$request->hasFile('cover')) {
            return $this->create($orm, $auth);
        }

        if ($request->file('cover')->error !== UPLOAD_ERR_OK) {
            return $this->create($orm, $auth);
        }

        if ($request->file('cover')->size > 1024 * 1024 * 2) {
            return $this->create($orm, $auth);
        }

        if ($request->file('cover')->type !== 'image/png') {
            return $this->create($orm, $auth);
        }

        $orm->getEntityManager()->persist($book)->run();

        $request->file('cover')->copy($app->file('public.img.cover.'.$book->id, 'png'));

        return redirect('/admin/books');
    }

    public function show(ORM $orm, Auth $auth, $target)
    {
        $book = $orm->getORM()->getRepository(Book::class)
                              ->select()
                              ->where('id', $target)
                              ->where('subjects.year.school.id', $auth->user()->year->school->id)
                              ->fetchOne();

        if ($book === null) {
            return error(404);
        }

        $subjects = $orm->getORM()->getRepository(Subject::class)
                                  ->select()
                                  ->where('year.school.id', $auth->user()->year->school->id)
                                  ->fetchAll();

        return template('admin.books.show', book: $book, subjects: $subjects);
    }

    public function update(ORM $orm, Auth $auth, Request $request, $target)
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

        $book = $orm->getORM()->getRepository(Book::class)
                              ->select()
                              ->where('id', $target)
                              ->where('subjects.year.school.id', $auth->user()->year->school->id)
                              ->fetchOne()
        ;


        $book->name = $request->get('name');
        $book->author = $request->get('author');
        $book->release_year = $request->get('release_year');
        $book->publisher = $request->get('publisher');

        $subjects = [];

        // we support max 16 subjects
        for ($index = 1; $index <= 16; $index++) {
            if (($id = $request->get("subject{$index}")) === null) {
                break;
            }

            $subjects[$id] = $orm->getORM()->getRepository(Subject::class)->findOne(['id' => $id, 'year.school.id' => $auth->user()->year->school->id]);
        }

        $book->subjects = array_values($subjects);

        $orm->getEntityManager()->persist($book)->run();


        return redirect('/admin/books'); 
    }

    public function delete(ORM $orm, Auth $auth, $target)
    {
        $book = $orm->getORM()->getRepository(Book::class)
                              ->select()
                              ->where('id', $target)
                              ->where('subjects.year.school.id', $auth->user()->year->school->id)
                              ->fetchOne()
        ;

        if ($book === null) {
            return error(404);
        }

        $orm->getEntityManager()->delete($book)->run();

        return redirect('/admin/books');
    }
}

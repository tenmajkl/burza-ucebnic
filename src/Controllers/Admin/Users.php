<?php

namespace App\Controllers\Admin;

use App\Contracts\Auth;
use App\Contracts\ORM;
use App\Entities\Ban;
use App\Entities\User;
use App\Entities\Year;
use DateTimeImmutable;
use DateTimeInterface;
use Lemon\Http\Request;
use Lemon\Templating\Template;
use Lemon\Validator;

class Users
{
    public function index(ORM $orm, Auth $auth): Template
    {
        $users = $orm->getORM()->getRepository(User::class)
                               ->select()
                               ->with('year')
                               ->with('year.school')
                               ->where('year.school.id', $auth->user()->year->school->id)
                               ->fetchAll()
        ;

        return template('admin.users.index', users: $users);
    }

    public function banMenu()
    {
        return template('admin.users.ban');
    }

    public function ban($target, ORM $orm, Auth $auth, Request $request)
    {
        $request->validate([
            'reason' => 'max:256',
            'expires' => 'date',
        ], template('admin.users.ban'));

        $user = $orm->getORM()->getRepository(User::class)->findOne([
            'id' => $target,
            'year.school.id' => $auth->user()->year->school->id,
        ]);
        if ($user === null) {
            return error(404);
        }

        $reason = $request->get('reason');
        $expires = DateTimeImmutable::createFromFormat('Y-m-d', $request->get('expires'));

        $ban = new Ban($reason, $expires, $user, $auth->user());
        $orm->getEntityManager()->persist($ban)->run();

        return redirect('/admin/users');
    }

    public function create(ORM $orm, Auth $auth)
    {
        $years = $orm->getORM()->getRepository(Year::class)
                               ->select()
                               ->with('school')
                               ->where('school.id', $auth->user()->year->school->id)
                               ->fetchAll()
        ;
        return template('admin.users.create', years: $years);
    }

    public function store(Request $request, Auth $auth, ORM $orm)
    {
        $request->validate([
            'name' => 'max:64',
            'year' => 'numeric',
        ], redirect('/admin/users/create'));

        $year = $orm->getORM()->getRepository(Year::class)->findOne([
            'id' => $request->get('year'),
        ]);

        if ($year === null) {
            return redirect('/admin/users/create');
        }

        $password = bin2hex(random_bytes(16));

        $user = new User(
            $request->get('name'),
            $password,
            $year,
            (int) ($request->get('admin') === 'on'),
        );
        $orm->getEntityManager()->persist($user)->run();

        return $this->index($orm, $auth)->with(['message' => $password]);
    }
}

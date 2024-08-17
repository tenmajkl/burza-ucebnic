<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Contracts\Auth;
use App\Contracts\ORM;
use App\Entities\Ban;
use App\Entities\User;
use App\Entities\Year;
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

    public function banMenu(?User $target, ORM $orm, Auth $auth)
    {
        if (null === $target || $target->isBanned() || $target->id === $auth->user()->id) {
            return error(404);
        }

        return template('admin.users.ban');
    }

    public function ban(?User $target, ORM $orm, Auth $auth, Request $request)
    {
        $request->validate([
            'reason' => 'max:256',
            'expires' => 'datetime',
        ], template('admin.users.ban'));

        if (null === $target || $target->isBanned() || $target->id === $auth->user()->id) {
            return error(404);
        }

        $reason = $request->get('reason');
        $expires = \DateTimeImmutable::createFromFormat('Y-m-d\\TH:i', $request->get('expires'));

        $ban = new Ban($reason, $expires, $target, $auth->user());
        $orm->getEntityManager()->persist($ban)->run();

        return redirect('/admin/users');
    }

    public function unban($target, ORM $orm, Auth $auth)
    {
        $ban = $orm->getORM()->getRepository(Ban::class)->findOne([
            'banned.id' => $target,
            'active' => 1,
            'banned.year.school.id' => $auth->user()->year->school->id,
        ]);

        if (null === $ban) {
            return error(404);
        }

        $ban->active = 0;
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
        ], $this->create($orm, $auth));

        $year = $orm->getORM()->getRepository(Year::class)->findOne([
            'id' => $request->get('year'),
        ]);

        if (null === $year) {
            Validator::addError('year', 'unknown-year', $request->get('year'));

            return $this->create($orm, $auth);
        }

        $password = bin2hex(random_bytes(16));

        $user = new User(
            $request->get('name'),
            password_hash($password, PASSWORD_DEFAULT),
            (int) ('on' === $request->get('admin')),
            null,
            $year,
        );
        $orm->getEntityManager()->persist($user)->run();

        return $this->index($orm, $auth)->with(message: text('admin.user-create-success').$password.'.');
    }

    public function show(?User $target, ORM $orm, Auth $auth)
    {
        if (null === $target) {
            return error(404);
        }

        $years = $orm->getORM()->getRepository(Year::class)
            ->select()
            ->with('school')
            ->where('school.id', $auth->user()->year->school->id)
            ->fetchAll()
        ;

        return template('admin.users.show', user: $target, years: $years);
    }

    public function update(?User $target, Request $request, ORM $orm, Auth $auth)
    {
        $request->validate([
            'name' => 'max:64',
            'year' => 'numeric',
        ], $this->show($target, $orm, $auth));

        if (null === $target) {
            return error(404);
        }

        if ($target->id === $auth->user()->id) {
            return error(400);
        }

        $year = $orm->getORM()->getRepository(Year::class)->findOne([
            'id' => $request->get('year'),
        ]);

        if (null === $year) {
            Validator::addError('year', 'unknown-year', $request->get('year'));

            return $this->show($target, $orm, $auth);
        }

        $target->email = $request->get('name');
        $target->year = $year;
        $target->role = (int) ('on' === $request->get('admin'));
        $orm->getEntityManager()->persist($target)->run();

        return $this->index($orm, $auth);
    }
}

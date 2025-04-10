<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Contracts\Auth;
use App\Contracts\ORM;
use App\Entities\Subject;
use App\Entities\Year;
use Lemon\Http\Request;

class Years
{
    public function index(ORM $orm, Auth $auth)
    {
        $years = $orm->getORM()->getRepository(Year::class)
            ->select()
            ->with('school')
            ->where('school.id', $auth->user()->year->school->id)
            ->fetchAll()
        ;

        return template('admin.years.index', years: $years);
    }

    public function create()
    {
        return template('admin.years.create');
    }

    public function store(ORM $orm, Auth $auth, Request $request)
    {
        $request->validate([
            'name' => 'max:32',
        ], template('admin.years.create'));

        $year = new Year($request->get('name'), $auth->user()->year->school, 1);

        for ($index = 1; $index <= 16; ++$index) {
            if (null === $request->get("subject{$index}")) {
                break;
            }
            $year->subjects[] = new Subject($request->get("subject{$index}"));
        }

        $year->visible = (int) ('on' === $request->get('visible'));

        $orm->getEntityManager()->persist($year)->run();

        return redirect('/admin/years');
    }

    public function show(ORM $orm, Auth $auth, ?Year $target)
    {
        if (null === $target) {
            return error(404);
        }

        if ('admins' === $target->name) {
            return error(404);
        }

        return template('admin.years.show', year: $target);
    }

    public function update(ORM $orm, Auth $auth, ?Year $target, Request $request)
    {
        $request->validate([
            'name' => 'max:32',
        ], template('admin.years.create'));

        if (null === $target) {
            return error(404);
        }

        if ('admins' === $target->name) {
            return error(404);
        }

        $target->name = $request->get('name');
        $target->subjects = [];

        for ($index = 1; $index <= 16; ++$index) {
            if (null === $request->get("subject{$index}")) {
                break;
            }
            $target->subjects[] = new Subject($request->get("subject{$index}"));
        }

        $target->visible = (int) ('on' === $request->get('visible'));

        $orm->getEntityManager()->persist($target)->run();

        return redirect('/admin/years');
    }

    public function delete(?Year $target, ORM $orm, Auth $auth)
    {
        if (null === $target) {
            return redirect('/admin/years');
        }

        if ('admins' === $target->name) {
            return redirect('/admin/years');
        }

        $orm->getEntityManager()->delete($target)->run();

        return redirect('/admin/years');
    }
}

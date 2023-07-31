<?php

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

        $year = new Year($request->get('name'), $auth->user()->year->school);

        for ($index = 1; $index <= 16; $index++) {
            if ($request->get("subject{$index}") === null) {
                break;
            }
            $year->subjects[] = new Subject($request->get("subject{$index}"));
        }
        $orm->getEntityManager()->persist($year)->run();

        return redirect('/admin/years');
    }

    public function show(ORM $orm, Auth $auth, $target)
    {
        $year = $orm->getORM()->getRepository(Year::class)->findOne([
            'id' => $target,
            'school.id' => $auth->user()->year->school->id,
        ]);
        if ($year === null) {
            return error(404);
        }
        return template('admin.years.show', year: $year);
    }

    public function update(ORM $orm, Auth $auth, $target, Request $request)
    {
        $request->validate([
            'name' => 'max:32',
        ], template('admin.years.create'));

        $year = $orm->getORM()->getRepository(Year::class)->findOne([
            'id' => $target,
            'school.id' => $auth->user()->year->school->id,
        ]); 

        if ($year === null) {
            return error(404);
        }

        $year->name = $request->get('name');
        $year->subjects = [];

        for ($index = 1; $index <= 16; $index++) {
            if ($request->get("subject{$index}") === null) {
                break;
            }
            $year->subjects[] = new Subject($request->get("subject{$index}"));
        }
        $orm->getEntityManager()->persist($year)->run();

        return redirect('/admin/years');
    }

    public function delete($target, ORM $orm, Auth $auth)
    {
        $year = $orm->getORM()->getRepository(Year::class)->findOne([
            'id' => $target,
            'school.id' => $auth->user()->year->school->id,
        ]);

        if ($year === null) {
            return redirect('/admin/years');
        }

        $orm->getEntityManager()->delete($year)->run();

        return redirect('/admin/years');
    }
}

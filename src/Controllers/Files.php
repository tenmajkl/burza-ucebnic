<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Contracts\Auth;
use App\Contracts\ORM;
use App\Entities\Offer;
use Lemon\Contracts\Support\Env;
use Lemon\Http\Responses\CustomResponse;
use Lemon\Kernel\Application;

class Files
{
    public function offer($image, Application $app, ORM $orm, Auth $auth)
    {
        if (null === $orm->getORM()->getRepository(Offer::class)->findOne(['id' => $image, 'book.subjects.year.school.id' => $auth->user()->year->school->id])) {
            return error(404);
        }

        $path = $app->file('storage.images.offers.'.$image, 'image');

        if (!is_file($path)) {
            return error(404);
        }

        $content = file_get_contents($path);
        [$info, $data] = explode(',', $content, 2);
        $data = base64_decode($data);

        $content_type = explode('data:', explode(';', $info)[0])[1];

        return new CustomResponse($data, 200, [
            'Content-Type' => $content_type,
        ]);
    }
}

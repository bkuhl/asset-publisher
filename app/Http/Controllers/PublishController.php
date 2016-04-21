<?php

namespace App\Http\Controllers;

use App\VCS\PublishRequestInterface;

class PublishController extends Controller
{
    public function publish(PublishRequestInterface $request)
    {
        dd($request);
    }
}

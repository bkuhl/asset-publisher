<?php

namespace App\Http\Controllers;

use App\Publisher;
use App\VCS\PublishRequestInterface;

class PublishController extends Controller
{
    public function publish(PublishRequestInterface $request, Publisher $publisher)
    {
        $publisher->publish($request->version(), $request->repository());

        return response();
    }
}

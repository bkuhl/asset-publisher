<?php

namespace App\Http\Controllers;

use App\VCS\GitHub\RepositoryEventFactory;
use Illuminate\Http\Request;

class RepositoryEventController extends Controller
{
    public function event(Request $request, RepositoryEventFactory $repositoryEventFactory)
    {
        $repositoryEvent = $repositoryEventFactory->make($request);
        $repositoryEvent->handle();

        return response('');
    }
}

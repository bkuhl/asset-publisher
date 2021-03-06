<?php

namespace App\VCS\GitHub;

use App\VCS\RepositoryEvent;
use App\VCS\UnhandledRepositoryEvent;
use App\VCS\UnrecognizedGitProvider;
use Illuminate\Http\Request;

class RepositoryEventFactory
{
    public function make(Request $request): RepositoryEvent
    {
        if ($this->isRecognizedProvider($request) === false) {
            throw new UnrecognizedGitProvider;
        }

        if ($request->header('X-GitHub-Event') === 'release') {
            return app(NewRelease::class, [$request]);
        }

        throw new UnhandledRepositoryEvent;
    }

    protected function isRecognizedProvider($request): bool
    {
        return $request->hasHeader('X-GitHub-Event');
    }
}

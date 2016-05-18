<?php

namespace App\VCS\GitHub;

use App\Publisher;
use App\VCS\PublishEvent;
use App\VCS\Repository;
use App\VCS\RepositoryEvent;
use App\Version;
use Illuminate\Http\Request;

class TagCreated extends PublishEvent
{
    /** @var Request */
    protected $request;

    public function __construct(Request $request, Publisher $publisher)
    {
        parent::__construct($publisher);
        
        $this->request = $request;
    }

    public function repository(): Repository
    {
        $namespace = $this->request->json('repository.owner.login');
        $repository = $this->request->json('repository.name');
        return app(Repository::class, [
            'git@github.com:'.$namespace.'/'.$repository.'.git',
            $namespace,
            $repository
        ]);
    }

    public function version(): Version
    {
        return app(Version::class, [$this->request->json('ref')]);
    }
}

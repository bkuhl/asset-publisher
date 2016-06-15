<?php

namespace App\VCS\GitHub;

use App\Publisher;
use App\VCS\PublishEvent;
use App\VCS\Repository;
use App\Version;
use Illuminate\Http\Request;

class NewRelease extends PublishEvent
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
        $repositoryName = explode('/', $this->request->json('repository.full_name'));
        $namespace = $repositoryName[0];
        $repository = $repositoryName[1];
        return app(Repository::class, [
            'git@github.com:'.$namespace.'/'.$repository.'.git',
            $namespace,
            $repository
        ]);
    }

    public function version(): Version
    {
        return app(Version::class, [$this->request->json('release.tag_name')]);
    }
}

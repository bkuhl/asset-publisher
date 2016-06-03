<?php

namespace Tests\App\VCS\GitHub;

use App\Publisher;
use App\VCS\GitHub\TagCreated;
use App\VCS\Repository;
use App\Version;
use Illuminate\Http\Request;
use Mockery;
use Mockery\MockInterface;

class TagCreatedTest extends \TestCase
{
    /** @var MockInterface */
    protected $request;

    /** @var MockInterface */
    protected $publisher;

    /** @var TagCreated */
    protected $event;

    public function setUp()
    {
        parent::setUp();

        $this->request = Mockery::mock(Request::class);
        $this->publisher = Mockery::mock(Publisher::class);

        $this->event = new TagCreated($this->request, $this->publisher);
    }

    /**
     * @test
     */
    public function suppliesRepository()
    {
        $repo = 'my-repo';
        $namespace = 'realpage';
        $this->request->shouldReceive('json')->with('repository.full_name')->andReturn($namespace.'/'.$repo);

        $repository = $this->event->repository();

        $this->assertInstanceOf(Repository::class, $repository);
        $this->assertEquals($repo, $repository->name());
        $this->assertEquals($namespace, $repository->namespace());
        $this->assertEquals('git@github.com:'.$namespace.'/'.$repo.'.git', $repository->sshUrl());
    }

    /**
     * @test
     */
    public function suppliesVersion()
    {
        $this->request->shouldReceive('json')->andReturn('v1.2.3');

        $version = $this->event->version();

        $this->assertInstanceOf(Version::class, $version);
    }
}
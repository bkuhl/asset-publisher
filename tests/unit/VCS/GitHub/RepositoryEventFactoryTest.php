<?php

namespace Tests\App\VCS\GitHub;

use App\VCS\GitHub\RepositoryEventFactory;
use App\VCS\RepositoryEvent;
use App\VCS\UnhandledRepositoryEvent;
use App\VCS\UnrecognizedGitProvider;
use Illuminate\Http\Request;
use Mockery\MockInterface;
use Mockery;

class RepositoryEventFactoryTest extends \TestCase
{
    /** @var MockInterface */
    protected $request;

    /** @var RepositoryEventFactory */
    protected $eventFactory;

    public function setUp()
    {
        parent::setUp();

        $this->request = Mockery::mock(Request::class);
        $this->eventFactory = new RepositoryEventFactory();
    }

    /**
     * @test
     */
    public function identifiesGitHubTagCreatedEvents()
    {
        $this->request->shouldReceive('hasHeader')->with('X-GitHub-Event')->andReturnTrue();
        $this->request->shouldReceive('header')->with('X-GitHub-Event')->andReturn('release');

        $this->assertInstanceOf(RepositoryEvent::class, $this->eventFactory->make($this->request));
    }

    /**
     * @test
     */
    public function complainsWhenGitProviderIsUnknown()
    {
        $this->expectException(UnrecognizedGitProvider::class);

        $this->request->shouldIgnoreMissing($returnValue = false);

        $this->eventFactory->make($this->request);
    }

    /**
     * @test
     */
    public function complainsWhenEventIsUnknown()
    {
        $this->expectException(UnhandledRepositoryEvent::class);

        $this->request->shouldReceive('hasHeader')->with('X-GitHub-Event')->andReturnTrue();
        $this->request->shouldIgnoreMissing();

        $this->eventFactory->make($this->request);
    }
}
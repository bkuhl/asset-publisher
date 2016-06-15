<?php

namespace Tests\App\VCS\GitHub;

use App\Publisher;
use App\VCS\GitHub\NewRelease;
use App\VCS\PublishEvent;
use App\VCS\Repository;
use App\Version;
use Illuminate\Http\Request;
use Mockery;
use Mockery\MockInterface;

class PublishEventTest extends \TestCase
{
    /** @var MockInterface */
    protected $publisher;

    /** @var MockInterface|NewRelease */
    protected $event;

    public function setUp()
    {
        parent::setUp();

        $this->publisher = Mockery::mock(Publisher::class);

        $this->event = Mockery::mock(PublishEvent::class.'[repository,version]', [$this->publisher]);
    }

    /**
     * @test
     */
    public function canPublish()
    {
        $version = Mockery::mock(Version::class);
        $repository = Mockery::mock(Repository::class);
        $this->event->shouldReceive('version')->andReturn($version);
        $this->event->shouldReceive('repository')->andReturn($repository);

        $this->publisher->shouldReceive('publish')->with($version, $repository)->andReturn(true);

        $this->assertTrue($this->event->handle());
    }
}
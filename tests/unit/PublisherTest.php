<?php

namespace Tests\App;

use App\Distribution\Distributor;
use App\Publisher;
use App\VCS\Git;
use App\VCS\Repository;
use Illuminate\Filesystem\Filesystem;
use Mockery;
use App\Version;

class PublisherTest extends \TestCase
{

    /** @var Mockery\MockInterface */
    protected $git;

    /** @var Mockery\MockInterface */
    protected $distributor;

    /** @var Mockery\MockInterface */
    protected $filesystem;

    public function setUp()
    {
        parent::setUp();

        $this->git = Mockery::mock(Git::class);
        $this->distributor = Mockery::mock(Distributor::class);
        $this->filesystem = Mockery::mock(Filesystem::class);
    }

    /**
     * @test
     */
    public function publishesMajorAndMinorVersions()
    {
        $publisher = new Publisher($this->git, $this->distributor, $this->filesystem);

        $version = Mockery::mock(Version::class, function (Mockery\MockInterface $mock) {
            $mock->shouldReceive('minorTag')->andReturn('v1.3');
            $mock->shouldReceive('patchTag')->andReturn('v1.3.2');
        });

        $path = uniqid();
        $repository = Mockery::mock(Repository::class);
        $repository->shouldReceive('path')->andReturn($path);

        // checks out the code locally
        $this->git->shouldReceive('clone')->with($repository)->andReturn($repository);
        $this->git->shouldReceive('checkoutTag')->with($repository, $version)->once();

        // assert minor and patch versions get published
        $this->distributor->shouldReceive('distribute')->with($path, 'v1.3')->once();
        $this->distributor->shouldReceive('distribute')->with($path, 'v1.3.2')->once();

        // cleanup temporary checkout
        $this->filesystem->shouldReceive('deleteDirectory')->with($path)->once();

        $this->assertTrue($publisher->publish($version, $repository));
    }
}
<?php

namespace Tests\App;

use App\Distribution\Distributor;
use App\VCS\Repository;
use Aws\Command;
use Aws\S3\S3Client;
use Mockery;
use Illuminate\Config\Repository as ConfigRepository;
use Log;

class DistributorTest extends \TestCase
{

    /** @var Mockery\MockInterface */
    protected $config;

    /** @var Mockery\MockInterface */
    protected $s3Client;

    /** @var Distributor */
    protected $distributor;

    public function setUp()
    {
        parent::setUp();

        $this->s3Client = Mockery::mock(S3Client::class);
        $this->app->instance('s3Client', $this->s3Client);

        $this->config = Mockery::mock(ConfigRepository::class);
        $this->app->instance('config', $this->config);

        $this->distributor = Mockery::mock(Distributor::class, ['distributorOptions']);
        $this->distributor->shouldDeferMissing();
    }

    /**
     * @test
     */
    public function distributesToSpecifiedBuildPath()
    {
        $awsBucket = uniqid();
        $buildPath = uniqid();
        $this->config->shouldReceive('get')->with('build.path')->andReturn($buildPath);
        $this->config->shouldReceive('get')->with('build.distribution.aws.bucket')->andReturn($awsBucket);
        $this->config->shouldReceive('get')->with('build.distribution.use_namespaces')->andReturnTrue();

        $version = uniqid();
        $path = uniqid();
        $name = uniqid();
        $distributorOptions = [uniqid()];
        $repository = Mockery::mock(Repository::class);
        $repository->shouldReceive('path')->andReturn($path);
        $repository->shouldReceive('name')->andReturn($name);

        $this->distributor->shouldReceive('distributorOptions')->andReturn($distributorOptions);

        // because of the closure nature involved with this test
        $this->s3Client->shouldReceive('uploadDirectory')->with(
            $path.DIRECTORY_SEPARATOR.$buildPath,
            $awsBucket,
            $name.DIRECTORY_SEPARATOR.$version,
            $distributorOptions
        )->once();

        Log::shouldReceive('info')->once();
        
        $this->distributor->distribute($repository, $version);
    }
}
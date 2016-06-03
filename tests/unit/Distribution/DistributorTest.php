<?php

namespace Tests\App;

use App\Distribution\Distributor;
use App\VCS\Repository;
use Aws\S3\S3Client;
use Mockery;
use Illuminate\Config\Repository as ConfigRepository;

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

        $this->distributor = new Distributor();
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

        $version = uniqid();
        $path = uniqid();
        $repository = Mockery::mock(Repository::class);
        $repository->shouldReceive('path')->andReturn($path);

        $this->s3Client->shouldReceive('uploadDirectory')->with($path.DIRECTORY_SEPARATOR.$buildPath, $awsBucket, $version)->once();

        $this->distributor->distribute($repository, $version);
    }
}
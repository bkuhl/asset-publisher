<?php

namespace Tests\App;

use App\VCS\GitLab\PublishRequest;
use App\VCS\Repository;
use App\Version;

class RepositoryTest extends \TestCase
{

    /** @var Repository */
    protected $repository;

    /** @var string */
    protected $sshUrl = 'git@github.com:realpage/npm-publisher.git';

    /** @var string */
    protected $namespace = 'realpage';

    /** @var string */
    protected $name = 'npm-publisher';

    public function setUp()
    {
        parent::setUp();

        $this->repository = new Repository($this->sshUrl, $this->namespace, $this->name);
    }

    /**
     * @test
     */
    public function suppliesSshUrl()
    {
        $this->assertEquals($this->sshUrl, $this->repository->sshUrl());
    }

    /**
     * @test
     */
    public function suppliesNamespace()
    {
        $this->assertEquals($this->namespace, $this->repository->namespace());
    }

    /**
     * @test
     */
    public function suppliesName()
    {
        $this->assertEquals($this->name, $this->repository->name());
    }

    /**
     * @test
     */
    public function carriesPath()
    {
        $path = uniqid();

        $this->repository->setPath($path);

        $this->assertEquals($path, $this->repository->path());
    }
}
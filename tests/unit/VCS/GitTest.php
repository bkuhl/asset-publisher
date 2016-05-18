<?php

namespace Tests\App;

use App\VCS\Git;
use App\VCS\Tagged;
use Mockery;
use App\VCS\Repository;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class GitTest extends \TestCase
{

    /** @var Mockery\MockInterface */
    protected $repository;

    public function setUp()
    {
        parent::setUp();

        $this->repository = Mockery::mock(Repository::class);
    }

    /**
     * @test
     */
    public function canCloneRepository()
    {
        $path = uniqid();

        $git = Mockery::mock(Git::class.'[temporaryPath]');
        $git->shouldAllowMockingProtectedMethods();
        $git->shouldReceive('temporaryPath')->andReturn($path);

        $repoNamespace = uniqid();
        $repoName = uniqid();
        $this->repository->shouldReceive('namespace')->andReturn($repoNamespace);
        $this->repository->shouldReceive('name')->andReturn($repoName);
        $this->repository->shouldReceive('path')->andReturn($path);
        $this->repository->shouldReceive('setPath')->with($path.DIRECTORY_SEPARATOR.$repoNamespace.'/'.$repoName)->once();
        $this->repository->shouldReceive('sshUrl')->andReturn(uniqid());

        // passing an empty string in place of a "command" that's normally passed
        $process = Mockery::mock(Process::class.'[setWorkingDirectory,run,isSuccessful]', ['']);
        $process->shouldReceive('setWorkingDirectory')->with($path)->once();
        $process->shouldReceive('run')->once();
        $process->shouldReceive('isSuccessful')->andReturnTrue();
        app()->instance(Process::class, $process);

        $this->assertEquals($this->repository, $git->clone($this->repository));
    }

    /**
     * @test
     */
    public function cloneRepositoryComplainsWhenFails()
    {
        $this->expectException(ProcessFailedException::class);

        $this->repository->shouldIgnoreMissing();

        // passing an empty string in place of a "command" that's normally passed
        $process = Mockery::mock(Process::class.'[isSuccessful]', ['']);
        $process->shouldReceive('isSuccessful')->andReturnFalse();
        $process->shouldIgnoreMissing();
        app()->instance(Process::class, $process);

        $git = new Git();
        $this->assertEquals($this->repository, $git->clone($this->repository));
    }

    /**
     * @test
     */
    public function canCheckoutTag()
    {
        $path = uniqid();
        $this->repository->shouldReceive('path')->andReturn($path);

        // passing an empty string in place of a "command" that's normally passed
        $process = Mockery::mock(Process::class.'[setWorkingDirectory,run,isSuccessful]', ['']);
        $process->shouldReceive('setWorkingDirectory')->with($path)->once();
        $process->shouldReceive('run')->once();
        $process->shouldReceive('isSuccessful')->andReturnTrue();
        app()->instance(Process::class, $process);

        $version = Mockery::mock(Tagged::class)->shouldIgnoreMissing();
        $git = new Git();
        $this->assertTrue($git->checkoutTag($this->repository, $version));
    }

    /**
     * @test
     */
    public function checkoutTagComplainsWhenFails()
    {
        $this->expectException(ProcessFailedException::class);

        $this->repository->shouldIgnoreMissing();

        // passing an empty string in place of a "command" that's normally passed
        $process = Mockery::mock(Process::class.'[isSuccessful]', ['']);
        $process->shouldReceive('isSuccessful')->andReturnFalse();
        $process->shouldIgnoreMissing();
        app()->instance(Process::class, $process);

        $version = Mockery::mock(Tagged::class)->shouldIgnoreMissing();
        $git = new Git();
        $this->assertTrue($git->checkoutTag($this->repository, $version));
    }
}
<?php

namespace Tests\App;

use App\VCS\Git;
use App\VCS\Tagged;
use Illuminate\Filesystem\Filesystem;
use Mockery;
use App\VCS\Repository;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class GitTest extends \TestCase
{

    /** @var Mockery\MockInterface */
    protected $repository;

    /** @var Mockery\MockInterface */
    protected $filesystem;

    /** @var Mockery\MockInterface */
    protected $version;

    /** @var Git */
    protected $git;

    public function setUp()
    {
        parent::setUp();

        $this->repository = Mockery::mock(Repository::class);
        $this->filesystem = Mockery::mock(Filesystem::class);
        $this->version = Mockery::mock(Tagged::class)->shouldIgnoreMissing();


        $this->git = new Git($this->filesystem);
    }

    /**
     * @test
     */
    public function canCloneRepository()
    {
        $path = uniqid();

        $this->git = Mockery::mock(Git::class.'[temporaryPath]', [$this->filesystem]);
        $this->git->shouldAllowMockingProtectedMethods();
        $this->git->shouldReceive('temporaryPath')->andReturn($path);

        $this->repository->shouldReceive('path')->andReturn($path);
        $this->repository->shouldReceive('setPath')->with($path)->once();
        $this->repository->shouldReceive('sshUrl')->andReturn(uniqid());

        // passing an empty string in place of a "command" that's normally passed
        $process = Mockery::mock(Process::class.'[setWorkingDirectory,run,isSuccessful]', ['']);
        $process->shouldReceive('setWorkingDirectory')->with($path)->once();
        $process->shouldReceive('run')->once();
        $process->shouldReceive('isSuccessful')->andReturnTrue();
        app()->instance(Process::class, $process);

        $this->assertEquals($this->repository, $this->git->clone($this->repository, $this->version));
    }

    /**
     * @test
     */
    public function cloneRepositoryComplainsWhenFails()
    {
        $this->expectException(ProcessFailedException::class);

        $this->filesystem->shouldReceive('makeDirectory')->once();

        $this->repository->shouldIgnoreMissing();

        // passing an empty string in place of a "command" that's normally passed
        $process = Mockery::mock(Process::class.'[isSuccessful]', ['']);
        $process->shouldReceive('isSuccessful')->andReturnFalse();
        $process->shouldIgnoreMissing();
        app()->instance(Process::class, $process);

        $this->assertEquals($this->repository, $this->git->clone($this->repository, $this->version));
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

        $this->assertTrue($this->git->checkoutTag($this->repository, $this->version));
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

        $this->assertTrue($this->git->checkoutTag($this->repository, $this->version));
    }
}
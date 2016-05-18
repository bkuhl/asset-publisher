<?php

namespace App\VCS;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class Git
{
    public function clone(Repository $repository) : Repository
    {
        $path = $this->temporaryPath();

        /** @var Process $process */
        $process = app(Process::class, ['git clone "'.$repository->sshUrl().'" .']);
        $process->setWorkingDirectory($path);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $repository->setPath($path.DIRECTORY_SEPARATOR.$repository->namespace().'/'.$repository->name());

        return $repository;
    }

    public function checkoutTag(Repository $repository, Tagged $version): bool
    {
        /** @var Process $process */
        $process = app(Process::class, ['git checkout tags/'.$version->minorTag()]);
        $process->setWorkingDirectory($repository->path());
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return true;
    }

    protected function temporaryPath(): string
    {
        return storage_path('tmp/'.uniqid());
    }
}

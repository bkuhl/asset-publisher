<?php

namespace App\VCS;

use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class Git
{

    public function checkout(Repository $repository)
    {
        $path = storage_path('tmp/'.uniqid());
        $process = new Process('git clone '.$repository->sshUrl().' '.$path);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return $path.DIRECTORY_SEPARATOR.$repository->namespace().'/'.$repository->name();
    }
}
<?php

namespace App;

use App\Distribution\Distributor;
use App\VCS\Git;
use App\VCS\Repository;
use Illuminate\Filesystem\Filesystem;

class Publisher
{
    /** @var Git */
    protected $git;

    /** @var Distributor */
    protected $distributor;

    /** @var Filesystem */
    protected $filesystem;

    public function __construct(Git $git, Distributor $distributor, Filesystem $filesystem)
    {
        $this->git = $git;
        $this->distributor = $distributor;
        $this->filesystem = $filesystem;
    }

    public function publish($version, Repository $repository)
    {
        $localPath = $this->git->checkout($repository);

        // distribute a version for each directory
        $prefix = 'v';
        $versions = explode('.', $version);
        foreach ($versions as $segment) {
            // v1, v1.0, v1.0.0
            $prefix .= $segment;

            $this->distributor->distribute($localPath, $prefix);

            // v1., v1.0.
            $prefix .= '.';
        }

        $this->filesystem->deleteDirectory($localPath);
    }
}
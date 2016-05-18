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

    public function publish(Version $version, Repository $repository): bool
    {
        $repository = $this->git->clone($repository);

        $this->git->checkoutTag($repository, $version);

        // We'll intentionally only provide a minor and patch reference so
        // we don't enable developers to skip QA, since major and minor versions
        // will definitely contain breaking changes
        $this->distributor->distribute($repository->path(), $version->patchTag());
        $this->distributor->distribute($repository->path(), $version->minorTag());

        // clean up temporary directory
        $this->filesystem->deleteDirectory($repository->path());

        return true;
    }
}

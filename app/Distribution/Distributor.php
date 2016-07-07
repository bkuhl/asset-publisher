<?php

namespace App\Distribution;

use App\VCS\Repository;
use Aws\Command;
use Aws\S3\S3Client;
use Illuminate\Config\Repository as ConfigRepository;
use Log;

class Distributor
{
    /** @var ConfigRepository */
    protected $config;

    /** @var S3Client */
    protected $s3Client;

    public function __construct()
    {
        // flysystem can't upload a directory, but the underlying adapter can
        $this->s3Client = app('s3Client');

        // injecting this via the constructor it doesn't contain
        // the config values we need
        $this->config = app('config');
    }

    public function distribute(Repository $repository, string $version)
    {
        $namespace = $version;
        if ($this->config->get('build.distribution.use_namespaces')) {
            $namespace = $repository->name().DIRECTORY_SEPARATOR.$namespace;
        }

        $this->s3Client->uploadDirectory(
            $repository->path().DIRECTORY_SEPARATOR.$this->config->get('build.path'),
            $this->config->get('build.distribution.aws.bucket'),
            $namespace,
            [
                'before'        => function (Command $command) {
                    $command['ACL'] = 'public-read';
                },
                'concurrency'   => 20
            ]
        );

        Log::info('Distributed version '.$version.' to S3 bucket '.$this->config->get('build.distribution.aws.bucket'));
    }
}

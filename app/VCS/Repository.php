<?php

namespace App\VCS;

class Repository
{
    /** @var string */
    protected $sshUrl;

    /** @var string */
    protected $name;

    /** @var string */
    protected $namespace;

    /** @var string */
    protected $path;

    public function __construct(string $sshUrl, string $namespace, string $name)
    {
        $this->sshUrl = $sshUrl;
        $this->namespace = $namespace;
        $this->name = $name;
    }

    //for some reason phpcs sees issues with these 2 methods
    //@codingStandardsIgnoreStart
    public function sshUrl(): string
    {
        return $this->sshUrl;
    }

    public function namespace(): string
    {
        return $this->namespace;
    }
    //@codingStandardsIgnoreEnd

    public function name(): string
    {
        return $this->name;
    }

    public function setPath(string $path)
    {
        $this->path = $path;
    }

    public function path(): string
    {
        return $this->path;
    }
}

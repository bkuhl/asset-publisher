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

    public function __construct($sshUrl, $namespace, $name)
    {
        $this->sshUrl = $sshUrl;
        $this->namespace = $namespace;
        $this->name = $name;
    }

    public function sshUrl()
    {
        return $this->sshUrl;
    }

    public function name()
    {
        return $this->name;
    }

    public function namespace()
    {
        return $this->namespace;
    }
}
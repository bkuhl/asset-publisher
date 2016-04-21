<?php

namespace App\VCS;

class Repository
{
    /** @var string */
    protected $sshUrl;

    public function __construct($sshUrl)
    {
        $this->sshUrl = $sshUrl;
    }

    public function sshUrl()
    {
        return $this->sshUrl;
    }
}
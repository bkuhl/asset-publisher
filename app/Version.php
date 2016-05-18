<?php

namespace App;

use App\VCS\Tagged;

class Version implements Tagged
{
    const PREFIX = 'v';
    
    private $version;

    public function __construct($version)
    {
        $this->version = explode('.', preg_replace('/[^0-9.]/', '', $version));
    }

    public function major(): int
    {
        if (isset($this->version[0])) {
            return intval($this->version[0]);
        }

        return 0;
    }

    public function minor(): int
    {
        if (isset($this->version[1])) {
            return intval($this->version[1]);
        }

        return 0;
    }

    public function patch(): int
    {
        if (isset($this->version[2])) {
            return intval($this->version[2]);
        }

        return 0;
    }

    public function minorTag(): string
    {
        return Version::PREFIX.implode('.', [
            $this->major(),
            $this->minor()
        ]);
    }

    public function patchTag(): string
    {
        return Version::PREFIX.implode('.', [
            $this->major(),
            $this->minor(),
            $this->patch()
        ]);
    }
}

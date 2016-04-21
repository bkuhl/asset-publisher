<?php

namespace App\Distribution;

use GrahamCampbell\Flysystem\FlysystemManager;
use Storage;

class Distributor
{
    /** @var FlysystemManager */
    protected $flysystem;

    public function __construct(FlysystemManager $flysystem)
    {
        $this->flysystem = $flysystem;
    }

    public function distribute($contents, $prefix)
    {
        $directories = $this->flysystem->listContents();
        if (!in_array($prefix, $directories)) {
            $this->flysystem->createDir($prefix);
        }

        $this->flysystem->copy($contents, $prefix.$contents);
    }
}
<?php

namespace App\VCS;

interface Tagged
{
    public function minorTag(): string;

    public function patchTag(): string;
}

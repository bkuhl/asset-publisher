<?php

namespace App\Distribution;

use Storage;

class Distributor
{
    public function distribute($contents, $prefix)
    {
        $directories = Storage::directories();
        if (!in_array($prefix, $directories)) {
            Storage::makeDirectory($prefix);
        }

        Storage::copy($contents, $prefix.$contents);
    }
}
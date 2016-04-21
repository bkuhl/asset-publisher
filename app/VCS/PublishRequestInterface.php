<?php

namespace App\VCS;


interface PublishRequestInterface
{
    public function repository();

    public function version();
}
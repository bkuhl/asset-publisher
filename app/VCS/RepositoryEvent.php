<?php

namespace App\VCS;

interface RepositoryEvent
{
    public function handle(): bool;
}

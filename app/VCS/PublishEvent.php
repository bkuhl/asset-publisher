<?php

namespace App\VCS;

use App\Publisher;
use App\Version;

abstract class PublishEvent implements RepositoryEvent
{

    /** @var Publisher */
    protected $publisher;
    
    public function __construct(Publisher $publisher)
    {
        $this->publisher = $publisher;
    }

    abstract public function repository(): Repository;

    abstract public function version(): Version;

    public function handle(): bool
    {
        return $this->publisher->publish($this->version(), $this->repository());
    }
}

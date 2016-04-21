<?php

namespace App\VCS\GitLab;

use App\VCS\PublishRequestInterface;
use App\VCS\Repository;
use Illuminate\Http\Request;

class PublishRequest extends Request implements PublishRequestInterface
{
    public function repository()
    {
        return new Repository($this->get('repository.git_ssh_url'));
    }
    
    public function version()
    {
        $tag = last(explode('/', $this->get('ref')));

        return str_replace('v', '', $tag);
    }
}
<?php

namespace App\VCS\GitLab;

use App\VCS\PublishRequestInterface;
use App\VCS\Repository;
use Illuminate\Http\Request;

class PublishRequest extends Request implements PublishRequestInterface
{
    public function repository()
    {
        $repoPath = explode('/', $this->get('project.path_with_namespace'));

        return new Repository(
            $this->get('repository.git_ssh_url'),
            $repoPath[0],
            $repoPath[1]
        );
    }
    
    public function version()
    {
        $tag = last(explode('/', $this->get('ref')));

        return str_replace('v', '', $tag);
    }
}
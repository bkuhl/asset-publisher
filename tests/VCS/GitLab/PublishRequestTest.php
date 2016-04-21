<?php

namespace Tests\App\VCS\GitLab;

use App\VCS\GitLab\PublishRequest;
use App\VCS\Repository;

class PublishRequestTest extends \TestCase
{

    /**
     * @test
     */
    public function suppliesVersionFromTag()
    {
        $request = new PublishRequest([], [
            'ref' => 'refs/tags/v1.0.0'
        ]);

        $this->assertEquals($request->version(), '1.0.0');
    }
}
<?php

namespace Tests\App;

class GitHubTest extends \TestCase
{

    /**
     * @test
     */
    public function respondsToGitHubHookAndDeploysToS3()
    {
        // for full request body, see https://developer.github.com/v3/activity/events/types/#createevent
        $this->json('POST', '/webhook', [
            "ref" => "v1.2.3",
            "ref_type" => "tag",
            "repository" => (object)[
                "name" => "asset-publisher",
                "full_name" => "realpage/asset-publisher",
                "owner" => (object)[
                    "login" => "realpage",
                ],
            ]
        ], [
            'X-GitHub-Event' => 'CreateEvent'
        ])->assertResponseOk();


    }
}
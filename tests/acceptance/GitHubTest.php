<?php

namespace Tests\App;

use GrahamCampbell\Flysystem\Facades\Flysystem;
use GrahamCampbell\Flysystem\FlysystemManager;
use TestCase;
use Log;

class GitHubTest extends \TestCase
{
    protected $patchVersion = 'v1.1.0';
    protected $minorVersion = 'v1.1';

    /** @var FlysystemManager */
    protected $flysystem;

    public function setUp()
    {
        parent::setUp();

        $this->flysystem = app('flysystem');

        # In case there was a previous failure, make sure we're starting clean
        if ($this->flysystem->has('/'.$this->patchVersion)) {
            $this->flysystem->deleteDir('/'.$this->patchVersion);
        }
        if ($this->flysystem->has('/'.$this->minorVersion)) {
            $this->flysystem->deleteDir('/' . $this->minorVersion);
        }
    }

    public function tearDown()
    {
        parent::tearDown();

        $this->flysystem->deleteDir('/'.$this->patchVersion);
        $this->flysystem->deleteDir('/'.$this->minorVersion);
    }

    /**
     * @test
     */
    public function respondsToGitHubHookAndDeploysToS3()
    {
        // avoid extra logging when running tests
        Log::shouldReceive('info');

        // for full request body, see https://developer.github.com/v3/activity/events/types/#createevent
        $this->json('POST', '/webhook/'.env('TOKEN'), [
            "ref" => "v1.1.0",
            "ref_type" => "tag",
            "repository" => (object)[
                "full_name" => "realpage/asset-publisher-test"
            ]
        ], [
            'X-GitHub-Event' => 'CreateEvent'
        ])
            ->assertResponseOk();

        $this->assertTrue($this->flysystem->has('/'.$this->minorVersion.'/deploy-me.json'));
        $this->assertTrue($this->flysystem->has('/'.$this->patchVersion.'/deploy-me.json'));
    }
}
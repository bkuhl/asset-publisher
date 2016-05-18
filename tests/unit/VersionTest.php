<?php

namespace Tests\App;

use App\VCS\GitLab\PublishRequest;
use App\VCS\Repository;
use App\VCS\Tagged;
use App\Version;

class VersionTest extends \TestCase
{

    /**
     * @test
     */
    public function canGetMajorVersionWithPrefix()
    {
        $version =  new Version('v11.2.3');

        $this->assertEquals(11, $version->major());
    }

    /**
     * @test
     */
    public function majorVersionDefaultsToZero()
    {
        $version =  new Version('');

        $this->assertEquals(0, $version->major());
    }

    /**
     * @test
     */
    public function canGetMinorVersion()
    {
        $version =  new Version('v1.22.3');

        $this->assertEquals(22, $version->minor());
    }

    /**
     * @test
     */
    public function minorVersionDefaultsToZero()
    {
        $version =  new Version('1');

        $this->assertEquals(0, $version->minor());
    }

    /**
     * @test
     */
    public function canGetPatchVersion()
    {
        $version =  new Version('v1.2.33');

        $this->assertEquals(33, $version->patch());
    }

    /**
     * @test
     */
    public function patchVersionDefaultsToZero()
    {
        $version =  new Version('1.2');

        $this->assertEquals(0, $version->patch());
    }

    /**
     * @test
     */
    public function buildsPatchTag()
    {
        $version =  new Version('1.2.10');

        $this->assertInstanceOf(Tagged::class, $version);

        $this->assertEquals('v1.2.10', $version->patchTag());
    }

    /**
     * @test
     */
    public function buildsMinorTag()
    {
        $version =  new Version('1.2.10');

        $this->assertEquals('v1.2', $version->minorTag());
    }
}
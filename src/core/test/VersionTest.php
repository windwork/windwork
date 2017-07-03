<?php
require_once __DIR__ . '/../lib/Version.php';

use wf\core\Version;

/**
 * Version test case.
 */
class VersionTest extends PHPUnit_Framework_TestCase
{


    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * Tests Version::getComponentsVersion()
     */
    public function testGetComponentVersion()
    {        
        $ret = Version::getComponentVersion();
        $this->assertNotEmpty($ret['release']);
        $this->assertNotEmpty($ret['component']['core']['version']);
        
        $coreVersion = Version::getComponentVersion('core');
        $this->assertEquals($ret['component']['core']['version'], $coreVersion['version']);
        $this->assertEquals($ret['component']['core']['time'], $coreVersion['time']);
    }

    /**
     * Tests Version::getReleaseTime()
     */
    public function testGetReleaseTime()
    {
        $release = Version::getReleaseTime();
        $this->assertNotEmpty($release);
    }
    
    /**
     * Tests Version::getComponentsVersion()
     */
    public function testGetRemoteComponentVersion()
    {
        $ret = Version::getRemoteComponentVersion();
        $this->assertNotEmpty($ret['component']['core']['version']);
        
        $coreVersion = Version::getComponentVersion('core');
        $this->assertEquals($ret['component']['core']['version'], $coreVersion['version']);
    }

    /**
     * Tests Version::getLatestReleaseTime()
     */
    public function testGetLatestReleaseTime()
    {
        // TODO Auto-generated VersionTest::testGetLatestReleaseTime()
        $this->markTestIncomplete("getLatestReleaseTime test not implemented");
        
        Version::getLatestReleaseTime(/* parameters */);
    }
}


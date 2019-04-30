<?php

namespace Pamo\DiceGame;

use Anax\DI\DIMagic;
use Anax\Response\ResponseUtility;
use Anax\Session\Session;
use PHPUnit\Framework\TestCase;

/**
 * Test the controller like it would be used from the router,
 * simulating the actual router paths and calling it directly.
 */
class DiceGameControllerTest extends TestCase
{
    private $controller;
    private $app;

    /**
     * Setup the controller, before each testcase, just like the router
     * would set it up.
     */
    protected function setUp(): void
    {
        global $di;
        // Init service container $di to contain $app as a service
        $di = new DIMagic();
        $di->loadServices(ANAX_INSTALL_PATH . "/config/di");
        $app = $di;
        $this->app = $app;
        $di->set("app", $app);

        // Create and initiate the controller
        $this->controller = new DiceGameController();
        $this->controller->setApp($app);
        $this->controller->initialize();
    }

    /**
     * Test get/post
     */
    public function testNewActionGet()
    {
        $res = $this->controller->newActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }

    /**
     * Test get/post
     */
    public function testSetupActionGet()
    {
        $res = $this->controller->setupActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }

    /**
     * Test get/post
     */
    public function testSetupActionPost()
    {
        $this->app->request->setGlobals([
            "post" => [
                "do-setup" => 1,
            ]
        ]);
        $res = $this->controller->setupActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $res);

        $this->app->request->setGlobals([
            "post" => [
                "do-init" => 1,
            ]
        ]);
        $res = $this->controller->setupActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }

    /**
     * Test get/post
     */
    public function testInitActionGet()
    {
        $this->app->session->set("player-names", ["Paul", "Hanna"]);
        $this->app->session->set("dice", 1);
        $res = $this->controller->initActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }

    /**
     * Test get/post
     */
    public function testStartActionGet()
    {
        $res = $this->controller->startActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }

    /**
     * Test get/post
     */
    public function testStartActionPost()
    {
        $this->app->request->setGlobals([
            "post" => [
                "do-start" => 1,
            ]
        ]);
        $res = $this->controller->startActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $res);

        $this->app->request->setGlobals([
            "post" => [
                "do-play" => 1,
            ]
        ]);
        $res = $this->controller->startActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $res);

        $this->app->request->setGlobals([
            "post" => [
                "do-computer" => 1,
            ]
        ]);
        $res = $this->controller->startActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }

    /**
     * Test get/post
     */
    public function testPlayActionGet()
    {
        $res = $this->controller->playActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }

    /**
     * Test get/post
     */
    public function testPlayActionPost()
    {
        $this->app->request->setGlobals([
            "post" => [
                "do-roll" => 1,
            ]
        ]);
        $res = $this->controller->playActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $res);

        $this->app->request->setGlobals([
            "post" => [
                "do-end" => 1,
            ]
        ]);
        $res = $this->controller->playActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $res);

        $this->app->request->setGlobals([
            "post" => [
                "do-reset" => 1,
            ]
        ]);
        $res = $this->controller->playActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }

    /**
     * Call the controller dump-app action GET.
     */
    public function testDumpAppActionGet()
    {
        $res = $this->controller->dumpAppActionGet();
        $this->assertIsString($res);
        $this->assertContains("app contains", $res);
    }

    /**
     * Call the controller catchAll ANY.
     */
    public function testCatchAllGet()
    {
        $res = $this->controller->catchAll();
        $this->assertNull($res);
    }
}

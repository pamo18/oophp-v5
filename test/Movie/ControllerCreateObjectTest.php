<?php

namespace Pamo\Movie;

use Anax\DI\DIMagic;
use Anax\Response\ResponseUtility;
use Anax\Session\Session;
use PHPUnit\Framework\TestCase;

/**
 * Test the controller like it would be used from the router,
 * simulating the actual router paths and calling it directly.
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class MovieControllerTest extends TestCase
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
        $this->controller = new MovieController();
        $this->controller->setApp($app);
        $_SERVER["SERVER_NAME"] = "0.0.0.0";
        $this->controller->initialize();
    }

    /**
     * Test get/post
     */
    public function testLoginActionGet()
    {
        $this->app->session->delete("logged-in");
        $res = $this->controller->loginActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);

        $this->app->session->set("logged-in", "doe");
        $res = $this->controller->loginActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }

    /**
     * Test get/post
     */
    public function testLoginActionPost()
    {
        $this->app->request->setGlobals([
            "post" => [
                "doLogin" => "login",
                "user" => "doe",
                "pass" => "doe"
            ]
        ]);

        $res = $this->controller->loginActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }

    /**
     * Test get/post
     */
    public function testShowAllActionGet()
    {
        $res = $this->controller->showAllActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);

        $this->app->session->delete("logged-in");
        $res = $this->controller->showAllActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }

    /**
     * Test get/post
     */
    public function testShowAllSortActionGet()
    {
        $this->app->session->set("logged-in", "doe");
        $res = $this->controller->showAllSortActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);

        $this->app->request->setGlobals([
            "get" => [
                "orderby" => "zzz",
                "order" => "asc"
            ]
        ]);

        try {
            $res = $this->controller->showAllSortActionGet();
            $this->assertInstanceOf(ResponseUtility::class, $res);
        } catch (MovieException $e) {
            echo "MovieException tested: " . $e->getMessage();
        }

        $this->app->request->setGlobals([
            "get" => [
                "orderby" => "id",
                "order" => "asc"
            ]
        ]);

        $this->app->session->delete("logged-in");
        $res = $this->controller->showAllSortActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }

    /**
     * Test get/post
     */
    public function testPaginateActionGet()
    {
        $this->app->session->set("logged-in", "doe");
        $_SERVER["QUERY_STRING"] = "movie";
        $res = $this->controller->showAllPaginateActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);

        $this->app->request->setGlobals([
            "get" => [
                "hits" => "20",
                "page" => "1",
                "order" => "zzz",
                "orderby" => "zzz"
            ]
        ]);

        try {
            $res = $this->controller->showAllPaginateActionGet();
            $this->assertInstanceOf(ResponseUtility::class, $res);
        } catch (MovieException $e) {
            echo "MovieException tested: " . $e->getMessage();
        }

        $this->app->request->setGlobals([
            "get" => [
                "hits" => "2",
                "page" => "-1",
                "order" => "asc",
                "orderby" => "id"
            ]
        ]);

        try {
            $res = $this->controller->showAllPaginateActionGet();
            $this->assertInstanceOf(ResponseUtility::class, $res);
        } catch (MovieException $e) {
            echo "MovieException tested: " . $e->getMessage();
        }

        $this->app->request->setGlobals([
            "get" => [
                "order" => "zzz",
                "orderby" => "xxx"
            ]
        ]);

        try {
            $res = $this->controller->showAllPaginateActionGet();
            $this->assertInstanceOf(ResponseUtility::class, $res);
        } catch (MovieException $e) {
            echo "MovieException tested: " . $e->getMessage();
        }

        $this->app->request->setGlobals([
            "get" => [
                "order" => "asc",
                "orderby" => "id"
            ]
        ]);

        $res = $this->controller->showAllPaginateActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);

        $this->app->session->delete("logged-in");
        $res = $this->controller->showAllPaginateActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }

    /**
     * Test get/post
     */
    public function testResetActionGet()
    {
        $this->app->session->set("logged-in", "doe");
        $res = $this->controller->resetActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);

        $this->app->session->delete("logged-in");
        $res = $this->controller->resetActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }

    /**
     * Test get/post
     */
    public function testResetActionPost()
    {
        $this->app->session->set("logged-in", "doe");
        $res = $this->controller->resetActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $res);

        $_SERVER["SERVER_NAME"] = "www.student.bth.se";
        $this->app->session->set("logged-in", "doe");
        $res = $this->controller->resetActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $res);

        $_SERVER["SERVER_NAME"] = "0.0.0.0";
        $this->app->request->setGlobals([
            "post" => [
                "reset" => "reset"
            ]
        ]);
        $res = $this->controller->resetActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $res);

        $this->app->request->setGlobals([
            "get" => [
                "reset" => "reset"
            ]
        ]);
        $res = $this->controller->resetActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $res);

        $this->app->session->delete("logged-in");
        $res = $this->controller->resetActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }

    /**
     * Test get/post
     */
    public function testSelectActionGet()
    {
        $this->app->session->set("logged-in", "doe");
        $res = $this->controller->selectActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);

        $this->app->session->delete("logged-in");
        $res = $this->controller->selectActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }

    /**
     * Test get/post
     */
    public function testSearchTitleActionGet()
    {
        $this->app->session->set("logged-in", "doe");

        $this->app->request->setGlobals([
            "get" => [
                "searchTitle" => "Pulp"
            ]
        ]);
        $res = $this->controller->searchTitleActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);

        $this->app->session->delete("logged-in");
        $res = $this->controller->searchTitleActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }

    /**
     * Test get/post
     */
    public function testSearchYearActionGet()
    {
        $this->app->session->set("logged-in", "doe");

        $this->app->request->setGlobals([
            "get" => [
                "year1" => "1990",
                "year2" => "2000"
            ]
        ]);
        $res = $this->controller->searchYearActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);

        $this->app->request->setGlobals([
            "get" => [
                "year1" => "1990"
            ]
        ]);
        $res = $this->controller->searchYearActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);

        $this->app->request->setGlobals([
            "get" => [
                "year2" => "2000"
            ]
        ]);
        $res = $this->controller->searchYearActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);

        $this->app->session->delete("logged-in");
        $res = $this->controller->searchYearActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }

    /**
     * Test get/post
     */
    public function testMovieSelectActionGet()
    {
        $this->app->session->set("logged-in", "doe");
        $res = $this->controller->movieSelectActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);

        $this->app->session->delete("logged-in");
        $res = $this->controller->movieSelectActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }

    /**
     * Test get/post
     */
    public function testMovieSelectActionPost()
    {
        $this->app->session->set("logged-in", "doe");
        $this->app->request->setGlobals([
            "post" => [
                "movieId" => -1,
                "doDelete" => "del"
            ]
        ]);
        $res = $this->controller->movieSelectActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $res);

        $this->app->request->setGlobals([
            "post" => [
                "doAdd" => "add"
            ]
        ]);
        $res = $this->controller->movieSelectActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $res);

        $this->app->request->setGlobals([
            "post" => [
                "movieId" => 1,
                "doEdit" => "edit"
            ]
        ]);
        $res = $this->controller->movieSelectActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $res);

        $this->app->session->delete("logged-in");
        $res = $this->controller->movieSelectActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }

    public function testMovieEditActionGet()
    {
        $this->app->session->set("logged-in", "doe");

        $this->app->request->setGlobals([
            "get" => [
                "movieId" => 2
            ]
        ]);
        $res = $this->controller->movieEditActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);


        $this->app->session->delete("logged-in");
        $res = $this->controller->movieEditActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }

    /**
     * Test get/post
     */
    public function testMovieEditActionPost()
    {
        $this->app->session->set("logged-in", "doe");
        $this->app->request->setGlobals([
            "post" => [
                "movieId" => 1,
                "movieTitle" => "testing",
                "movieYear" => 2019,
                "movieImage" => "testing",
                "doSave" => "save"
            ]
        ]);
        $res = $this->controller->movieEditActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $res);


        $this->app->session->delete("logged-in");
        $res = $this->controller->movieSelectActionPost();
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

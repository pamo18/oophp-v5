<?php

namespace Pamo\Content;

use Anax\Commons\AppInjectableInterface;
use Anax\Commons\AppInjectableTrait;
use Pamo\TextFilter\MyTextFilter;

// use Anax\Route\Exception\ForbiddenException;
// use Anax\Route\Exception\NotFoundException;
// use Anax\Route\Exception\InternalErrorException;

/**
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
class BlogController implements AppInjectableInterface
{
    use AppInjectableTrait;

    /**
     * @var string
     * @var string
     * @var object
     */
    private $db = "not active";
    private $base = "";
    private $filter;



    /**
     *
     * Init the content database with setup details and redirect to start page
     *
     * @return void
     */
    public function initialize()
    {
        $this->db = "active";
        $this->base = "blog/";
        $this->app->db->connect();
        $this->filter = new MyTextFilter();
    }



    /**
     * Show blogs
     *
     * @return object
     */
    public function viewActionGet($slug = null) : object
    {
        $db = $this->app->db;
        $page = $this->app->page;
        $response = $this->app->response;

        $sql = <<<EOD
SELECT
    *,
    DATE_FORMAT(COALESCE(updated, published), '%Y-%m-%dT%TZ') AS published_iso8601,
    DATE_FORMAT(COALESCE(updated, published), '%Y-%m-%d') AS published
FROM content
WHERE
    slug = ?
    AND type = ?
    AND (deleted IS NULL OR deleted > NOW())
    AND published <= NOW()
ORDER BY published DESC
;
EOD;
        $content = $db->executeFetch($sql, [$slug, "post"]);
        if (!$content) {
            return $response->redirect("../content/blogs");
        }

        $title = $content->title;
        $data = [
            "title" => $this->filter->parse($content->title, "esc"),
            "author" => $this->filter->parse($content->author, "esc"),
            "published_iso8601" => $this->filter->parse($content->published_iso8601, "esc"),
            "published" => $this->filter->parse($content->published, "esc"),
            "data" => ($content->filter ? $this->filter->parse($content->data, explode(",", $content->filter)) : $this->filter->parse($content->data, "esc"))
        ];

        $page->add("content/nav2");
        $page->add("blog/view", $data);

        return $page->render([
            "title" => $title,
        ]);
    }




    /**
     * This sample method dumps the content of $app.
     * GET mountpoint/dump-app
     *
     * @return string
     */
    public function dumpAppActionGet() : string
    {
        // Deal with the action and return a response.
        $services = implode(", ", $this->app->getServices());
        return __METHOD__ . "<p>\$app contains: $services";
    }



    /**
     * Adding an optional catchAll() method will catch all actions sent to the
     * router. You can then reply with an actual response or return void to
     * allow for the router to move on to next handler.
     * A catchAll() handles the following, if a specific action method is not
     * created:
     * ANY METHOD mountpoint/**
     *
     * @param array $args as a variadic parameter.
     *
     * @return mixed
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function catchAll(...$args)
    {
        // Deal with the request and send an actual response, or not.
        //return __METHOD__ . ", \$db is {$this->db}, got '" . count($args) . "' arguments: " . implode(", ", $args);
        return;
    }
}

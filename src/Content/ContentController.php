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
class ContentController implements AppInjectableInterface
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
        $this->base = "content/";
        $this->app->db->connect();
        $this->filter = new MyTextFilter();
    }



    /**
     * Login Page
     *
     * @return object
     */
    public function loginActionGet() : object
    {
        $response = $this->app->response;
        $page = $this->app->page;
        $title = "Login";

        if ($this->app->session->get("logged-in")) {
            return $response->redirect($this->base . "admin");
        } else {
            $page->add("content/access");

            return $page->render([
                "title" => $title,
            ]);
        }
    }



    /**
     * Post login Page
     *
     * @return object
     */
    public function loginActionPost() : object
    {
        $db = $this->app->db;
        $response = $this->app->response;
        $request = $this->app->request;

        if ($request->getPost("doLogin")) {
            $user = $request->getPost("user");
            $pass = $request->getPost("pass");
            $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
            $resultset = $db->executeFetchAll($sql, [$user, $pass]);
            if ($resultset) {
                $this->app->session->set("logged-in", $user);
            }
        }
        return $response->redirect($this->base . "login");
    }



    /**
     * Show all content
     *
     * @return object
     */
    public function showAllActionGet() : object
    {
        $db = $this->app->db;
        $page = $this->app->page;
        $title = "Show all content";

        $sql = "SELECT * FROM content WHERE deleted is null AND published;";
        $data = [
            "resultset" => $db->executeFetchAll($sql)
        ];
        $page->add("content/nav2");
        $page->add("content/show-all", $data);

        return $page->render([
            "title" => $title,
        ]);
    }



    /**
     * Show pages
     *
     * @return object
     */
    public function pagesActionGet() : object
    {
        $db = $this->app->db;
        $page = $this->app->page;
        $title = "View pages";

        $sql = <<<EOD
SELECT
    *,
    CASE
        WHEN (deleted <= NOW()) THEN "Deleted"
        WHEN (published <= NOW()) THEN "Published"
        ELSE "Not Published"
    END AS status
FROM content
WHERE type=? AND deleted is null AND published
;
EOD;

        $data = [
            "resultset" => $db->executeFetchAll($sql, ["page"])
        ];
        $page->add("content/nav2");
        $page->add("content/pages", $data);

        return $page->render([
            "title" => $title,
        ]);
    }



    /**
     * Show blogs
     *
     * @return object
     */
    public function blogsAction() : object
    {
        $db = $this->app->db;
        $page = $this->app->page;
        $sql = <<<EOD
SELECT
    *,
    DATE_FORMAT(COALESCE(updated, published), '%Y-%m-%dT%TZ') AS published_iso8601,
    DATE_FORMAT(COALESCE(updated, published), '%Y-%m-%d') AS published
FROM content
WHERE type=? AND deleted is null AND published
ORDER BY published DESC
;
EOD;

        $title = "View blogs";
        $data = [
            "resultset" => $db->executeFetchAll($sql, ["post"])
        ];

        $page->add("content/nav2");
        $page->add("content/blogs", $data);

        return $page->render([
            "title" => $title,
        ]);
    }



    /**
     * Reset database from sql file
     *
     * @return object
     */
    public function resetActionGet() : object
    {
        if (!$this->app->session->get("logged-in")) {
            return $this->app->response->redirect($this->base . "login");
        }
        $page = $this->app->page;
        $session = $this->app->session;
        $title = "Resetting the database";

        $data = [
            "output" => $session->get("output")
        ];
        $session->delete("output");
        $page->add("content/nav1");
        $page->add("content/reset", $data);

        return $page->render([
            "title" => $title,
        ]);
    }



    /**
     * Post reset Page
     *
     * @return object
     */
    public function resetActionPost() : object
    {
        $response = $this->app->response;
        $request = $this->app->request;
        $session = $this->app->session;
        $output = null;
        $file   = dirname(__FILE__) . "/../../sql/content/setup.sql";

        if ($_SERVER["SERVER_NAME"] === "www.student.bth.se") {
            $mysql  = "/usr/bin/mysql";
        } else {
            $mysql  = "/usr/local/mysql-8.0.14-macos10.14-x86_64/bin/mysql";
        }

        $dsnDetail = getDatabaseConfig();
        $host = $dsnDetail[1];
        $database = $dsnDetail[2];
        $login = $dsnDetail[3];
        $password = $dsnDetail[4];

        if ($request->getPost("reset") || $request->getGet("reset")) {
            $command = "$mysql -h{$host} -u{$login} -p{$password} $database < $file 2>&1";
            $output = [];
            $status = null;
            exec($command, $output, $status);
            $output = "<p>The command was: <code>$command</code>.<br>The command exit status was $status."
                . "<br>The output from the command was:</p><pre>"
                . print_r($output, 1)
                . "</pre>";
            $session->set("output", $output);
            $this->app->session->set("message", "Database reset!");
        }
        return $response->redirect($this->base . "reset");
    }




    /**
     * Create content
     *
     * @return object
     */
    public function createActionGet() : object
    {
        if (!$this->app->session->get("logged-in")) {
            return $this->app->response->redirect($this->base . "login");
        }
        $page = $this->app->page;
        $title = "Create content";

        $page->add("content/nav1");
        $page->add("content/create");

        return $page->render([
            "title" => $title,
        ]);
    }



    /**
     * Post create content
     *
     * @return object
     */
    public function createActionPost() : object
    {
        $db = $this->app->db;
        $response = $this->app->response;
        $request = $this->app->request;
        $directTo = $this->base;

        if ($request->getPost("doCreate")) {
            $new = $request->getPost("contentTitle");
            $author = $this->app->session->get("logged-in");
            $sql = "INSERT INTO content (title, author) VALUES (?, ?);";
            $db->execute($sql, [$new, $author]);
            $id = $db->lastInsertId();
            $directTo .= "edit?id=$id";
            $this->app->session->set("message", "Content created!");
        }
        return $response->redirect($directTo);
    }




    /**
     * Admin content
     *
     * @return object
     */
    public function adminActionGet() : object
    {
        if (!$this->app->session->get("logged-in")) {
            return $this->app->response->redirect($this->base . "login");
        }
        $db = $this->app->db;
        $request = $this->app->request;
        $page = $this->app->page;
        $title = "Admin content";

        // Only these values are valid
        $columns = ["id", "title", "type", "path", "slug", "published", "created", "updated", "deleted"];
        $orders = ["asc", "desc"];

        // Get settings from GET or use defaults
        $orderBy = $request->getGet("orderby") ?: "id";
        $order = $request->getGet("order") ?: "asc";

        // Incoming matches valid value sets
        if (!(in_array($orderBy, $columns) && in_array($order, $orders))) {
            throw new ContentException("Not valid input for sorting.");
        }

        // Get number of hits per page
        $hits = $request->getGet("hits", 8);
        if (!(is_numeric($hits) && $hits > 0 && $hits <= 8)) {
            throw new ContentException("Not valid for hits.");
        }

        // Get max number of pages
        $sql = "SELECT COUNT(id) AS max FROM content;";
        $max = $db->executeFetchAll($sql);
        $max = ceil($max[0]->max / $hits);

        // Get current page
        $currentPage = $request->getGet("page", 1);
        // Check valid page value
        if (!(is_numeric($currentPage) && $currentPage > 0)) {
            throw new ContentException("Not valid for page.");
        }
        if (($currentPage * $hits) > ($max * $hits)) {
            $currentPage = 1;
        }

        $offset = $hits * ($currentPage - 1);

        $sql = "SELECT * FROM content ORDER BY $orderBy $order LIMIT $hits OFFSET $offset;";
        $resultset = $db->executeFetchAll($sql);

        $data = [
            "resultset" => $resultset,
            "defaultRoute" => "?",
            "max" => $max
        ];

        $page->add("content/nav1");
        $page->add("content/admin", $data);

        return $page->render([
            "title" => $title,
        ]);
    }



    /**
     * Edit content
     *
     * @return object
     */
    public function editActionGet() : object
    {
        if (!$this->app->session->get("logged-in")) {
            return $this->app->response->redirect($this->base . "login");
        }
        $db = $this->app->db;
        $page = $this->app->page;
        $request = $this->app->request;
        $title = "Edit content";
        $contentId = $request->getGet("id");
        if (!is_numeric($contentId)) {
            throw new ContentException("Not valid for content id.");
        }
        $sql = "SELECT * FROM content WHERE id = ?;";
        $content = $db->executeFetch($sql, [$contentId]);

        $data = [
            "id" => $this->filter->parse($content->id, "esc"),
            "title" => $this->filter->parse($content->title, "esc"),
            "path" => $this->filter->parse($content->path, "esc"),
            "slug" => $this->filter->parse($content->slug, "esc"),
            "data" => $this->filter->parse($content->data, "esc"),
            "type" => $this->filter->parse($content->type, "esc"),
            "filter" => $this->filter->parse($content->filter, "esc"),
            "published" => $this->filter->parse($content->published, "esc")
        ];

        $page->add("content/nav1");
        $page->add("content/edit", $data);

        return $page->render([
            "title" => $title,
        ]);
    }



    /**
     * Post edit content
     *
     * @return object
     */
    public function editActionPost() : object
    {
        $db = $this->app->db;
        $response = $this->app->response;
        $request = $this->app->request;
        $directTo = $this->base;
        $contentId = $request->getPost("contentId");

        if (!is_numeric($contentId)) {
            throw new ContentException("Not valid for content id.");
        }

        if ($request->getPost("doSave")) {
            $list = [
                "contentTitle",
                "contentPath",
                "contentSlug",
                "contentData",
                "contentType",
                "contentFilter",
                "contentPublish",
                "contentId"
            ];
            $params = [];
            foreach ($list as $param) {
                $params[$param] = $request->getPost($param);
            }

            if (!$params["contentSlug"]) {
                $params["contentSlug"] = slugify($params["contentTitle"]);
            }

            $sql = "SELECT COUNT(*) AS count FROM content WHERE slug = ? AND NOT id = ?";
            $result = $db->executeFetch($sql, [$params["contentSlug"], $params["contentId"]]);

            if ($result->count) {
                $params["contentSlug"] .= "-" . $params["contentId"];
            }

            if (!$params["contentPath"]) {
                $params["contentPath"] = null;
            }

            if (!$params["contentPublish"]) {
                $params["contentPublish"] = null;
            }

            if (is_array($params["contentFilter"])) {
                $params["contentFilter"] = implode(",", $params["contentFilter"]);
            }

            $sql = "UPDATE content SET title=?, path=?, slug=?, data=?, type=?, filter=?, published=? WHERE id = ?;";
            $db->execute($sql, array_values($params));
            $directTo .= "edit?id=$contentId";
            $this->app->session->set("message", "Content saved!");
        } else if ($request->getPost("doDelete")) {
            $directTo .= "delete?id=$contentId";
        }
        return $response->redirect($directTo);
    }



    /**
     * Delete content
     *
     * @return object
     */
    public function deleteActionGet() : object
    {
        if (!$this->app->session->get("logged-in")) {
            return $this->app->response->redirect($this->base . "login");
        }
        $db = $this->app->db;
        $page = $this->app->page;
        $request = $this->app->request;
        $title = "Delete content";
        $contentId = $request->getGet("id");

        if (!is_numeric($contentId)) {
            throw new ContentException("Not valid for content id.");
        }

        $sql = "SELECT id, title FROM content WHERE id = ?;";
        $content = $db->executeFetch($sql, [$contentId]);

        $data = [
            "id" => $this->filter->parse($content->id, "esc"),
            "title" => $this->filter->parse($content->title, "esc")
        ];

        $page->add("content/nav1");
        $page->add("content/delete", $data);

        return $page->render([
            "title" => $title,
        ]);
    }



    /**
     * Post delete content
     *
     * @return object
     */
    public function deleteActionPost() : object
    {
        $db = $this->app->db;
        $response = $this->app->response;
        $request = $this->app->request;
        $directTo = $this->base;
        $contentId = $request->getPost("contentId") ?? null;

        if (!is_numeric($contentId)) {
            throw new ContentException("Not valid for content id.");
        }

        if ($request->getPost("doDelete")) {
            $sql = "UPDATE content SET deleted=NOW() WHERE id=?;";
            $db->execute($sql, [$contentId]);
            $directTo .= "admin";
            $this->app->session->set("message", "Content deleted!");
        }
        return $response->redirect($directTo);
    }



    /**
     * Recover content
     *
     * @return object
     */
    public function recoverActionGet() : object
    {
        if (!$this->app->session->get("logged-in")) {
            return $this->app->response->redirect($this->base . "login");
        }
        $db = $this->app->db;
        $page = $this->app->page;
        $request = $this->app->request;
        $title = "Recover content";
        $contentId = $request->getGet("id");

        if (!is_numeric($contentId)) {
            throw new ContentException("Not valid for content id.");
        }

        $sql = "SELECT id, title FROM content WHERE id = ?;";
        $content = $db->executeFetch($sql, [$contentId]);

        $data = [
            "id" => $this->filter->parse($content->id, "esc"),
            "title" => $this->filter->parse($content->title, "esc")
        ];

        $page->add("content/nav1");
        $page->add("content/recover", $data);

        return $page->render([
            "title" => $title,
        ]);
    }



    /**
     * Post recover content
     *
     * @return object
     */
    public function recoverActionPost() : object
    {
        $db = $this->app->db;
        $response = $this->app->response;
        $request = $this->app->request;
        $directTo = $this->base;
        $contentId = $request->getPost("contentId") ?? null;

        if (!is_numeric($contentId)) {
            throw new ContentException("Not valid for content id.");
        }

        if ($request->getPost("doRecover")) {
            $sql = "UPDATE content SET deleted=null WHERE id=?;";
            $db->execute($sql, [$contentId]);
            $directTo .= "admin";
            $this->app->session->set("message", "Content recovered!");
        }
        return $response->redirect($directTo);
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

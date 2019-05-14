<?php

namespace Pamo\Movie;

use Anax\Commons\AppInjectableInterface;
use Anax\Commons\AppInjectableTrait;

// use Anax\Route\Exception\ForbiddenException;
// use Anax\Route\Exception\NotFoundException;
// use Anax\Route\Exception\InternalErrorException;

/**
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
class MovieController implements AppInjectableInterface
{
    use AppInjectableTrait;

    /**
     * @var string
     * @var string
     */
    private $db = "not active";
    private $base = "";



    /**
     *
     * Init the movie database with setup details and redirect to start page
     *
     * @return void
     */
    public function initialize()
    {
        $this->db = "active";
        $this->base = "movie/";
        $this->app->db->connect();
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
            return $response->redirect($this->base . "show-all");
        } else {
            $page->add("movie/access");

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
     * Show all movies
     *
     * @return object
     */
    public function showAllActionGet() : object
    {
        $db = $this->app->db;
        $page = $this->app->page;
        $title = "Show all movies";

        $sql = "SELECT * FROM movie;";
        $data = [
            "resultset" => $db->executeFetchAll($sql)
        ];
        $page->add("movie/nav");
        $page->add("movie/show-all", $data);

        return $page->render([
            "title" => $title,
        ]);
    }



    /**
     * Show all moves while sortable
     *
     * @return object
     */
    public function showAllSortActionGet() : object
    {
        $db = $this->app->db;
        $request = $this->app->request;
        $page = $this->app->page;
        $title = "Show and sort all movies";

        // Only these values are valid
        $columns = ["id", "title", "year", "image"];
        $orders = ["asc", "desc"];

        // Get settings from GET or use defaults
        $orderBy = $request->getGet("orderby") ?: "id";
        $order = $request->getGet("order") ?: "asc";

        // Incoming matches valid value sets
        if (!(in_array($orderBy, $columns) && in_array($order, $orders))) {
            throw new MovieException("Not valid input for sorting.");
        }

        $sql = "SELECT * FROM movie ORDER BY $orderBy $order;";
        $data = [
            "resultset" => $db->executeFetchAll($sql)
        ];
        $page->add("movie/nav");
        $page->add("movie/show-all-sort", $data);

        return $page->render([
            "title" => $title,
        ]);
    }



    /**
     * Show all movies, paginate
     *
     * @return object
     */
    public function showAllPaginateActionGet() : object
    {
        $db = $this->app->db;
        $request = $this->app->request;
        $page = $this->app->page;
        $title = "Show, paginate movies";

        // Get number of hits per page
        $hits = $request->getGet("hits", 4);
        if (!(is_numeric($hits) && $hits > 0 && $hits <= 8)) {
            throw new MovieException("Not valid for hits.");
        }

        // Get max number of pages
        $sql = "SELECT COUNT(id) AS max FROM movie;";
        $max = $db->executeFetchAll($sql);
        $max = ceil($max[0]->max / $hits);

        // Get current page
        $currentPage = $request->getGet("page", 1);
        // Check valid page value
        if (!(is_numeric($currentPage) && $currentPage > 0)) {
            throw new MovieException("Not valid for page.");
        }
        if (($currentPage * $hits) > ($max * $hits)) {
            $currentPage = 1;
        }

        $offset = $hits * ($currentPage - 1);

        $columns = ["id", "title", "year", "image"];
        $orders = ["asc", "desc"];

        $orderBy = $request->getGet("orderby", null) ?: "id";
        $order = $request->getGet("order", null) ?: "asc";

        if (!(in_array($orderBy, $columns) && in_array($order, $orders))) {
            throw new MovieException("Not valid input for sorting.");
        }

        $sql = "SELECT * FROM movie ORDER BY $orderBy $order LIMIT $hits OFFSET $offset;";
        $data = [
            "resultset" => $db->executeFetchAll($sql),
            "defaultRoute" => "?",
            "max" => $max
        ];
        $page->add("movie/nav");
        $page->add("movie/show-all-paginate", $data);

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
        $page->add("movie/nav");
        $page->add("movie/reset", $data);

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
        $file   = dirname(__FILE__) . "/../../sql/movie/setup.sql";

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
        }
        return $response->redirect($this->base . "reset");
    }



    /**
     * Movie management, CRUD
     *
     * @return object
     */
    public function selectActionGet() : object
    {
        $db = $this->app->db;
        $page = $this->app->page;
        $title = "SELECT *";

        $sql = "SELECT * FROM movie;";
        $data = [
            "sql" => $sql,
            "resultset" => $db->executeFetchAll($sql)
        ];

        $page->add("movie/nav");
        $page->add("movie/select", $data);

        return $page->render([
            "title" => $title,
        ]);
    }



    /**
     * Search for a movie titel
     *
     * @return object
     */
    public function searchTitleActionGet() : object
    {
        $db = $this->app->db;
        $request = $this->app->request;
        $page = $this->app->page;
        $title = "SELECT * WHERE title";
        $searchTitle = $request->getGet("searchTitle", null);
        if ($searchTitle) {
            $sql = "SELECT * FROM movie WHERE title LIKE ?;";
            $resultset = $db->executeFetchAll($sql, [$searchTitle]);
        }
        $data = [
            "searchTitle" => $searchTitle,
            "resultset" => $resultset ?? null
        ];

        $page->add("movie/nav");
        $page->add("movie/search-title", $data);
        $page->add("movie/show-all", $data);

        return $page->render([
            "title" => $title,
        ]);
    }



    /**
     * Search for a movie year
     *
     * @return object
     */
    public function searchYearActionGet() : object
    {
        $db = $this->app->db;
        $request = $this->app->request;
        $page = $this->app->page;
        $title = "SELECT * WHERE year";
        $year1 = $request->getGet("year1", null);
        $year2 = $request->getGet("year2", null);
        if ($year1 && $year2) {
            $sql = "SELECT * FROM movie WHERE year >= ? AND year <= ?;";
            $resultset = $db->executeFetchAll($sql, [$year1, $year2]);
        } elseif ($year1) {
            $sql = "SELECT * FROM movie WHERE year >= ?;";
            $resultset = $db->executeFetchAll($sql, [$year1]);
        } elseif ($year2) {
            $sql = "SELECT * FROM movie WHERE year <= ?;";
            $resultset = $db->executeFetchAll($sql, [$year2]);
        }

        $data = [
            "year1" => $year1,
            "year2" => $year2,
            "resultset" => $resultset ?? null
        ];

        $page->add("movie/nav");
        $page->add("movie/search-year", $data);
        $page->add("movie/show-all", $data);

        return $page->render([
            "title" => $title,
        ]);
    }



    /**
     * Movie management, CRUD
     *
     * @return object
     */
    public function movieSelectActionGet() : object
    {
        if (!$this->app->session->get("logged-in")) {
            return $this->app->response->redirect($this->base . "login");
        }
        $db = $this->app->db;
        $page = $this->app->page;
        $title = "Select a movie";
        $sql = "SELECT id, title FROM movie;";

        $data = [
            "movies" => $db->executeFetchAll($sql)
        ];

        $page->add("movie/nav");
        $page->add("movie/movie-select", $data);

        return $page->render([
            "title" => $title,
        ]);
    }



    /**
     * Post movie management Page
     *
     * @return object
     */
    public function movieSelectActionPost() : object
    {
        $db = $this->app->db;
        $response = $this->app->response;
        $request = $this->app->request;
        $movieId = $request->getPost("movieId");
        $directTo = $this->base;

        if ($request->getPost("doDelete")) {
            $sql = "DELETE FROM movie WHERE id = ?;";
            $db->execute($sql, [$movieId]);
            $directTo .= "movie-select";
        } elseif ($request->getPost("doAdd")) {
            $sql = "INSERT INTO movie (title, year, image) VALUES (?, ?, ?);";
            $db->execute($sql, ["A title", 2017, "img/noimage.png"]);
            $movieId = $db->lastInsertId();
            $directTo .= "movie-edit?movieId=$movieId";
        } elseif ($request->getPost("doEdit") && is_numeric($movieId)) {
            $directTo .= "movie-edit?movieId=$movieId";
        }

        return $response->redirect($directTo);
    }



    /**
     * Edit a movie
     *
     * @return object
     */
    public function movieEditActionGet() : object
    {
        if (!$this->app->session->get("logged-in")) {
            return $this->app->response->redirect($this->base . "login");
        }
        $db = $this->app->db;
        $request = $this->app->request;
        $page = $this->app->page;
        $title = "UPDATE movie";
        $movieId = $request->getPost("movieId") ?: $request->getGet("movieId");
        $sql = "SELECT * FROM movie WHERE id = ?;";
        $movie = $db->executeFetchAll($sql, [$movieId]);

        $data = [
            "movie" => $movie[0]
        ];

        $page->add("movie/nav");
        $page->add("movie/movie-edit", $data);

        return $page->render([
            "title" => $title,
        ]);
    }



    /**
     * Post edit movie Page
     *
     * @return object
     */
    public function movieEditActionPost() : object
    {
        $db = $this->app->db;
        $response = $this->app->response;
        $request = $this->app->request;
        $movieId = $request->getPost("movieId") ?: $request->getGet("movieId");
        $movieTitle = $request->getPost("movieTitle");
        $movieYear  = $request->getPost("movieYear");
        $movieImage = $request->getPost("movieImage");

        if ($request->getPost("doSave")) {
            $sql = "UPDATE movie SET title = ?, year = ?, image = ? WHERE id = ?;";
            $db->execute($sql, [$movieTitle, $movieYear, $movieImage, $movieId]);
        }

        return $response->redirect($this->base . "movie-edit?movieId=$movieId");
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

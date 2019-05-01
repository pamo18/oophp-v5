<?php

namespace Pamo\DiceGame;

use Anax\Commons\AppInjectableInterface;
use Anax\Commons\AppInjectableTrait;

// use Anax\Route\Exception\ForbiddenException;
// use Anax\Route\Exception\NotFoundException;
// use Anax\Route\Exception\InternalErrorException;

/**
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class DiceGameController implements AppInjectableInterface
{
    use AppInjectableTrait;

    /**
     * @var string
     */
    private $db = "not active";
    private $base = "";



    /**
     *
     * @return void
     */
    public function initialize()
    {
        $this->db = "active";
        $this->base = "dice100/";
    }



    /**
     * Reset the game session and redirect to setup page
     *
     * @return object
     */
    public function newActionGet() : object
    {
        $this->app->session->destroy();
        return $this->app->response->redirect($this->base . "setup");
    }



    /**
     * Setup Page: number of players, names and number of dice
     *
     * @return object
     */
    public function setupActionGet() : object
    {
        $session = $this->app->session;
        $page = $this->app->page;

        $title = "Setup the game";
        $data = [
            "numPlayers" => $session->get("num-players") ?? null
        ];
        $page->add("dice/setup", $data);

        return $page->render([
            "title" => $title,
        ]);
    }



    /**
     * Post Setup Page
     *
     * @return object
     */
    public function setupActionPost() : object
    {
        $response = $this->app->response;
        $session = $this->app->session;
        $request = $this->app->request;
        $directTo = $this->base;

        if ($request->getPost("do-setup")) {
            $session->set("num-players", $request->getPost("num-players"));
            $session->set("dice", $request->getPost("dice"));
            $directTo .= "setup";
        } else if ($request->getPost("do-init")) {
            $session->set("player-names", $request->getPost("player-names"));
            $directTo .= "init";
        }
        return $response->redirect($directTo);
    }



    /**
     * Init the game with setup details and redirect to start page
     *
     * @return object
     */
    public function initActionGet() : object
    {
        $response = $this->app->response;
        $session = $this->app->session;

        $playerNames = $session->get("player-names");
        $dice = $session->get("dice");
        $game = new Game($playerNames, $dice);
        $session->set("dice-game", $game);
        return $response->redirect($this->base . "start");
    }



    /**
     * Start page: decides who goes first and players turn
     *
     * @return object
     */
    public function startActionGet() : object
    {
        $session = $this->app->session;
        $page = $this->app->page;

        $title = "Who's turn is it";
        $game = $session->get("dice-game");

        $data = [
            "winningThrow" => $game->showWinningThrow(),
            "player" => $game->showPlayersTurn(),
            "status" => $game->checkScore()
        ];
        $page->add("dice/start", $data);

        return $page->render([
            "title" => $title,
        ]);
    }



    /**
     * Post Start Page
     *
     * @return object
     */
    public function startActionPost() : object
    {
        $response = $this->app->response;
        $session = $this->app->session;
        $request = $this->app->request;
        $directTo = $this->base;

        $game = $session->get("dice-game");
        if ($request->getPost("do-start")) {
            $game->decideWhoStarts();
            $directTo .= "start";
        } else if ($request->getPost("do-play")) {
            $directTo .= "play";
        } else if ($request->getPost("do-computer")) {
            $game->playRound("Computer");
            $directTo .= "play";
        }
        return $response->redirect($directTo);
    }



    /**
     * Play page
     *
     * @return object
     */
    public function playActionGet() : object
    {
        $session = $this->app->session;
        $page = $this->app->page;

        $title = "Play the game!";
        $game = $session->get("dice-game");
        $round = $game->showRound();
        $data = [
            "roundScore" => $round["score"],
            "histogram" => $round["histogram"],
            "throws" => $round["throws"],
            "handFaces" => $round["faces"],
            "player" => $game->showPlayersTurn(),
            "gameScore" => $game->showPlayerScore(),
            "status" => $game->checkScore(),
            "computer" => $game->showPlayersTurn() == "Computer" ?? null
        ];
        $page->add("dice/play", $data);

        return $page->render([
            "title" => $title
        ]);
    }



    /**
     * Post Play Page
     *
     * @return object
     */
    public function playActionPost() : object
    {
        $response = $this->app->response;
        $session = $this->app->session;
        $request = $this->app->request;
        $directTo = $this->base;

        $game = $session->get("dice-game");
        if ($request->getPost("do-roll")) {
            $game->playRound();
            $directTo .= "play";
        } else if ($request->getPost("do-end")) {
            $game->endRound();
            $directTo .= "start";
        } else if ($request->getPost("do-reset")) {
            $directTo .= "new";
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

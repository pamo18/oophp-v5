<?php
/**
 * Create routes using $app programming style.
 */
//var_dump(array_keys(get_defined_vars()));
//var_dump($app->session);

/**
 * Reset the game session and redirect to setup page
 */
$app->router->get("dice-game/new-game", function () use ($app) {
    $app->session->destroy();
    return $app->response->redirect("dice-game/setup");
});

/**
 * Setup Page: number of players, names and number of dice
 */
$app->router->get("dice-game/setup", function () use ($app) {
    $title = "Setup the game";
    $data = [
        "numPlayers" => $app->session->get("num-players") ?? null
    ];
    $app->page->add("dice/setup", $data);

    return $app->page->render([
        "title" => $title,
    ]);
});

$app->router->post("dice-game/setup", function () use ($app) {
    if ($app->request->getPost("do-setup")) {
        $app->session->set("num-players", $app->request->getPost("num-players"));
        $app->session->set("dice", $app->request->getPost("dice"));
        $directTo = "dice-game/setup";
    } else if ($app->request->getPost("do-init")) {
        $app->session->set("player-names", $app->request->getPost("player-names"));
        $directTo = "dice-game/init";
    }
    return $app->response->redirect($directTo);
});

/**
 * Init the game with setup details and redirect to start page
 */
$app->router->get("dice-game/init", function () use ($app) {
    $playerNames = $app->session->get("player-names");
    $dice = $app->session->get("dice");
    $game = new Pamo\DiceGame\Game($playerNames, $dice);
    $app->session->set("dice-game", $game);
    return $app->response->redirect("dice-game/start");
});

/**
 * Start page: decides who goes first and players turn
 */
$app->router->get("dice-game/start", function () use ($app) {
    $title = "Who's turn is it";
    $game = $app->session->get("dice-game");

    $data = [
        "winningThrow" => $game->showWinningThrow(),
        "player" => $game->showPlayersTurn(),
        "status" => $game->checkScore()
    ];
    $app->page->add("dice/start", $data);

    return $app->page->render([
        "title" => $title,
    ]);
});

$app->router->post("dice-game/start", function () use ($app) {
    $game = $app->session->get("dice-game");
    if ($app->request->getPost("do-start")) {
        $game->decideWhoStarts();
        $directTo = "dice-game/start";
    } else if ($app->request->getPost("do-play")) {
        $directTo = "dice-game/play";
    } else if ($app->request->getPost("do-computer")) {
        $game->playRound("Computer");
        $directTo = "dice-game/play";
    }
    return $app->response->redirect($directTo);
});

/**
 * Game page
 */
$app->router->get("dice-game/play", function () use ($app) {
    $title = "Play the game!";
    $game = $app->session->get("dice-game");
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
    $app->page->add("dice/play", $data);

    return $app->page->render([
        "title" => $title
    ]);
});

$app->router->post("dice-game/play", function () use ($app) {
    $game = $app->session->get("dice-game");
    if ($app->request->getPost("do-roll")) {
        $game->playRound();
        $redirectTo = "dice-game/play";
    } else if ($app->request->getPost("do-end")) {
        $game->endRound();
        $redirectTo = "dice-game/start";
    } else if ($app->request->getPost("do-reset")) {
        $redirectTo = "dice-game/new-game";
    }
    return $app->response->redirect($redirectTo);
});

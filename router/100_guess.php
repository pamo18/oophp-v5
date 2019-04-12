<?php
/**
 * Create routes using $app programming style.
 */
//var_dump(array_keys(get_defined_vars()));

/**
 * Init the game with redirect
 */
$app->router->get("guess/init", function () use ($app) {
    $game = new Pamo\Guess\Guess();
    $_SESSION = [];
    $_SESSION["number"] = $game->number();
    $_SESSION["tries"] = $game->tries();

    return $app->response->redirect("guess/play");
});

/**
 * Reset the game with redirect
 */
$app->router->post("guess/game-reset", function () use ($app) {
        echo "Resetting game!";
    return $app->response->redirect("guess/init");
});

/**
 * Manage a guess
 */
$app->router->post("guess/game-guess", function () use ($app) {
    $number = $_SESSION["number"];
    $tries = $_SESSION["tries"];
    $guess = $_POST["guess"];

    $game = new Pamo\Guess\Guess($number, $tries);

    try {
        $_SESSION["result"] = $game->makeGuess($guess);
    } catch (Pamo\Guess\GuessException $e) {
        echo "Got exception: " . get_class($e) . "<hr>";
        $_SESSION["result"] = $e->getMessage();
    } catch (TypeError $e) {
        echo "Got exception: " . get_class($e) . "<hr>";
        $_SESSION["result"] = $e->getMessage();
    }
    $_SESSION["tries"] = $game->tries();

    return $app->response->redirect("guess/play");
});

/**
 * Show number
 */
$app->router->post("guess/game-cheat", function () use ($app) {
    $_SESSION["show"] = $_SESSION["number"];

    return $app->response->redirect("guess/play");
});

/**
 * Play the game.
 */
$app->router->get("guess/play", function () use ($app) {
    $title = "Play the game!";

    $guess = $_SESSION["guess"] ?? null;
    $result = $_SESSION["result"] ?? null;
    $tries = $_SESSION["tries"] ?? null;
    $show = $_SESSION["show"] ?? null;

    $_SESSION["guess"] = null;
    $_SESSION["result"] = null;
    $_SESSION["show"] = null;

    $data = [
        "tries" => $tries,
        "number" => $number ?? null,
        "guess" => $guess ?? null,
        "result" => $result,
        "show" => $show ?? null
    ];
    $app->page->add("guess/play", $data);
    //$app->page->add("guess/debug");

    return $app->page->render([
        "title" => $title,
    ]);
});

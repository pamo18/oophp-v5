<?php

namespace Pamo\TextFilter;

/**
 * Create routes using $app programming style.
 */
//var_dump(array_keys(get_defined_vars()));



/**
 * Test bbcode
 */
$app->router->get("lek/text-bbcode", function () use ($app) {
    $title = "Textfilter for bbcode";
    $filter = new MyTextFilter();
    $text = file_get_contents(__DIR__ . "/../view/textfilter/text/bbcode.txt");
    $html = $filter->parse($text, "bbcode");

    $data = [
        "text" => $text,
        "html" => $html
    ];

    $app->page->add("textfilter/bbcode", $data);

    return $app->page->render([
        "title" => $title,
    ]);
});



/**
 * Test clickable
 */
$app->router->get("lek/text-clickable", function () use ($app) {
    $title = "Textfilter for clickable";
    $filter = new MyTextFilter();
    $text = file_get_contents(__DIR__ . "/../view/textfilter/text/clickable.txt");
    $html = $filter->parse($text, ["link"]);

    $data = [
        "text" => $text,
        "html" => $html
    ];

    $app->page->add("textfilter/clickable", $data);

    return $app->page->render([
        "title" => $title,
    ]);
});



/**
 * Test markdown
 */
$app->router->get("lek/text-markdown", function () use ($app) {
    $title = "Textfilter for markdown";
    $filter = new MyTextFilter();
    $text = file_get_contents(__DIR__ . "/../view/textfilter/text/sample.md");
    $html = $filter->parse($text, ["markdown"]);

    $data = [
        "text" => $text,
        "html" => $html
    ];

    $app->page->add("textfilter/markdown", $data);

    return $app->page->render([
        "title" => $title,
    ]);
});



/**
 * Test nl2br
 */
$app->router->get("lek/text-nl2br", function () use ($app) {
    $title = "Textfilter for nl2br";
    $filter = new MyTextFilter();
    $text = file_get_contents(__DIR__ . "/../view/textfilter/text/bbcode.txt");
    $html = $filter->parse($text, ["bbcode", "nl2br"]);

    $data = [
        "text" => $text,
        "html" => $html
    ];

    $app->page->add("textfilter/nl2br", $data);

    return $app->page->render([
        "title" => $title,
    ]);
});



/**
 * Showing message Hello World, not using the standard page layout.
 */
$app->router->get("lek/hello-world", function () use ($app) {
    // echo "Some debugging information";
    return "Hello World";
});



/**
 * Returning a JSON message with Hello World.
 */
$app->router->get("lek/hello-world-json", function () use ($app) {
    // echo "Some debugging information";
    return [["message" => "Hello World"]];
});



/**
* Showing message Hello World, rendered within the standard page layout.
 */
$app->router->get("lek/hello-world-page", function () use ($app) {
    $title = "Hello World as a page";
    $data = [
        "class" => "hello-world",
        "content" => "Hello World in " . __FILE__,
    ];

    $app->page->add("anax/v2/article/default", $data);

    return $app->page->render([
        "title" => $title,
    ]);
});

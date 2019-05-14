<article>
    <header>
        <h1><?= $title ?></h1>
        <p><i>Published: <time datetime="<?= $published_iso8601 ?>" pubdate><?= $published ?></time></i></p>
    </header>
    <?= $data ?>
    <p><i>Written By: <?= $author ?></i></p>
</article>

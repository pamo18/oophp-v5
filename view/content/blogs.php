<?php
if (!$resultset) {
    return;
}
?>

<article>

<?php foreach ($resultset as $row) : ?>
<section>
    <header>
        <h1><a href="../blog/view/<?= esc($row->slug) ?>"><?= esc($row->title) ?></a></h1>
        <p><i>Published: <time datetime="<?= esc($row->published_iso8601) ?>" pubdate><?= esc($row->published) ?></time></i></p>
    </header>
    <?= substr(esc($row->data), 0, 300) ?> <a href="../blog/view/<?= esc($row->slug) ?>">Läs mer »</a>
</section>
<?php endforeach; ?>

</article>

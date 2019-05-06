<?php
if (!$resultset) {
    return;
}
?>

<p>Items per page:
    <a href="<?= mergeQueryString(["hits" => 2], $defaultRoute) ?>">2</a> |
    <a href="<?= mergeQueryString(["hits" => 4], $defaultRoute) ?>">4</a> |
    <a href="<?= mergeQueryString(["hits" => 8], $defaultRoute) ?>">8</a>
</p>

<table class="movie">
    <thead>
        <tr class="first">
            <th>Rad</th>
            <th>Id <?= orderby2("id", $defaultRoute) ?></th>
            <th>Bild <?= orderby2("image", $defaultRoute) ?></th>
            <th>Titel <?= orderby2("title", $defaultRoute) ?></th>
            <th>År <?= orderby2("year", $defaultRoute) ?></th>
        </tr>
    </thead>
<?php $id = -1; foreach ($resultset as $row) :
    $id++; ?>
    <tr>
        <td><?= $id ?></td>
        <td><?= $row->id ?></td>
        <td><img src="../image/movie/<?= $row->image ?>?w=200&crop-to-fit&aspect-ratio=16:10" alt="<?= $row->image ?>"></td>
        <td><?= $row->title ?></td>
        <td><?= $row->year ?></td>
    </tr>
<?php endforeach; ?>
</table>

<p>
    Pages:
    <?php for ($i = 1; $i <= $max; $i++) : ?>
        <a href="<?= mergeQueryString(["page" => $i], $defaultRoute) ?>"><?= $i ?></a>
    <?php endfor; ?>
</p>

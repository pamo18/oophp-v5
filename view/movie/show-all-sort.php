<?php
if (!$resultset) {
    return;
}
$defaultRoute = "?"
?>

<table class="movie">
    <thead>
        <tr class="first">
            <th>Rad</th>
            <th>Id <?= orderby("id", $defaultRoute) ?></th>
            <th>Bild <?= orderby("image", $defaultRoute) ?></th>
            <th>Titel <?= orderby("title", $defaultRoute) ?></th>
            <th>År <?= orderby("year", $defaultRoute) ?></th>
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

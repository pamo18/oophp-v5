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

<table class="content fixed">
    <thead>
        <tr class="first">
            <th style="width:5%">Id <?= orderby("id", $defaultRoute) ?></th>
            <th style="width:18%">Title <?= orderby("title", $defaultRoute) ?></th>
            <th style="width:8%">Type</th>
            <th style="width:8%">Path</th>
            <th style="width:10%">Slug</th>
            <th style="width:11%">Published <?= orderby("published", $defaultRoute) ?></th>
            <th style="width:11%">Created <?= orderby("created", $defaultRoute) ?> </th>
            <th style="width:11%">Updated <?= orderby("updated", $defaultRoute) ?></th>
            <th style="width:11%">Deleted <?= orderby("deleted", $defaultRoute) ?></th>
            <th style="width:7%">Actions</th>
        </tr>
    </thead>
<?php $id = -1; foreach ($resultset as $row) :
    $id++; ?>
    <tr>
        <td><?= $row->id ?></td>
        <td><?= $row->title ?></td>
        <td><?= $row->type ?></td>
        <td><?= $row->path ?></td>
        <td><?= $row->slug ?></td>
        <td><?= $row->published ?></td>
        <td><?= $row->created ?></td>
        <td><?= $row->updated ?></td>
        <td><?= $row->deleted ?></td>
        <td>
            <a class="icons blue" href="edit?id=<?= $row->id ?>" title="Edit this content">
                <i class="fas fa-edit"></i>
            </a>
            <a class="icons red" href="delete?id=<?= $row->id ?>" title="Edit this content">
                <i class="fas fa-trash-alt"></i>
            </a>
            <?php if ($row->deleted) { ?>
            <a class="icons green" href="recover?id=<?= $row->id ?>" title="Edit this content">
                <i class="fas fa-undo-alt"></i>
            </a>
            <?php } ?>
        </td>
    </tr>
<?php endforeach; ?>
</table>

<p>
    Pages:
    <?php for ($i = 1; $i <= $max; $i++) : ?>
        <a href="<?= mergeQueryString(["page" => $i], $defaultRoute) ?>"><?= $i ?></a>
    <?php endfor; ?>
</p>

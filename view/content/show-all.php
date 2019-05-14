<?php
if (!$resultset) {
    return;
}
?>

<table class="content fixed">
    <thead>
        <tr class="first">
            <th style="width:5%">Id</th>
            <th style="width:25%">Title</th>
            <th style="width:10%">Path</th>
            <th style="width:18%">Slug</th>
            <th style="width:9.5%">Published</th>
            <th style="width:9.5%">Created</th>
            <th style="width:9.5%">Updated</th>
            <th style="width:9.5%">Deleted</th>
        </tr>
    </thead>
<?php foreach ($resultset as $row) : ?>
    <tr>
        <td><?= $row->id ?></td>
        <td><?= $row->title ?></td>
        <td><?= $row->path ?></td>
        <td><?= $row->slug ?></td>
        <td><?= $row->published ?></td>
        <td><?= $row->created ?></td>
        <td><?= $row->updated ?></td>
        <td><?= $row->deleted ?></td>
    </tr>
<?php endforeach; ?>
</table>

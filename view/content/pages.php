<?php
if (!$resultset) {
    return;
}
?>

<table class="content">
    <thead>
        <tr class="first">
            <th>Id</th>
            <th>Title</th>
            <th>Type</th>
            <th>Status</th>
            <th>Published</th>
            <th>Author</th>
        </tr>
    </thead>
<?php $id = -1; foreach ($resultset as $row) :
    $id++; ?>
    <tr>
        <td><?= $row->id ?></td>
        <td><a class="icons blue" href="../page/view/<?= $row->path ?>"><?= $row->title ?></a></td>
        <td><?= $row->type ?></td>
        <td><?= $row->status ?></td>
        <td><?= $row->published ?></td>
        <td><?= $row->author ?></td>
    </tr>
<?php endforeach; ?>
</table>

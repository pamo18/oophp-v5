
<h1 class="center">My Content Database</h1>
<div class="menu">
        <div class="movieNav">
        <a href="admin">Admin</a>
        <a href="create">Create</a>
        <a href="reset">Reset database</a>
        <a href="show-all">View</a>
    </div>

<?php if ($app->session->get("message")) { ?>
    <div id="message" class="center"><?= $app->session->get("message") ?></div>
<?php }
$app->session->delete("message")
?>
</div>

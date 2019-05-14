
<h1 class="center">My Content Database</h1>
<div class="menu">
    <div class="movieNav">
        <?php
        if ($_SERVER["SERVER_NAME"] === "www.student.bth.se") {
            $url = "/~pamo18/dbwebb-kurser/oophp/me/redovisa/htdocs/content";
        } else {
            $url = "/oophp/me/redovisa/htdocs/content";
        }
        ?>
        <a href="<?= $url ?>/show-all">View all</a>
        <a href="<?= $url ?>/pages">View Pages</a>
        <a href="<?= $url ?>/blogs">View Blogs</a>
        <a href="<?= $url ?>/admin">Admin</a>
    </div>
</div>

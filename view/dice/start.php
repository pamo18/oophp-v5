<?php

namespace Anax\View;

?>
<?php if ($status == "new") { ?>
<h1 class="center">First round, Who will start?</h1>
<p class="center">The player with the highest throw will start the game first!</p>
<form class="center guess" action="start" method="post">
    <input class="button" type="submit" name="do-start" value="Roll the dice">
</form>

<?php } else { ?>
    <h1 class="center">New round</h1>
    <?php if ($winningThrow) { ?>
            <h4 class="center">The highest throw was <?= $winningThrow ?> thrown by <?= $player ?></h4>
            <br>
            <div class="dice-graphic center">
                <i class="dice-sprite <?= "dice-" . $winningThrow ?>"></i>
            </div>
    <?php } ?>
    <h3 class="center"><?= $player ?>'s turn'</h3>
    <form class="center guess" action="start" method="post">
        <input class="button" type="submit" name="do-play" value="Play!">
    </form>
<?php } ?>

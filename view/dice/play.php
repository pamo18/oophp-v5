<?php

namespace Anax\View;

?>
<h1 class="center">Dice 100</h1>
<h3 class="bold center">Player: <?= $player ?></h3>
<p class="bold center">Throws in this round: <?= $throws ?></p>
<p class="center">Total score: <?= $gameScore + $roundScore ?> </p>
<p class="center">Round score: <?= $roundScore ?></p>
<div class="dice-graphic center">
    <?php if ($status == "start") { ?>
        <i class="dice-sprite dice-1"></i>
        <p class="center">Roll the dice but dont get a 1!</p>
    <?php } else { ?>
        <?php foreach ($handFaces as $face) { ?>
            <i class="dice-sprite <?= "dice-" . $face ?>"></i>
        <?php } ?>
        <p class="center">Last roll: <?= implode(", ", $handFaces) ?></p>
    <?php } ?>
</div>
<?php if ($status == "one") { ?>
    <p class="center">You got a <span class="red">ONE</span> and lose all points from this round!</p>
<?php } ?>

<?php if ($status == "win") { ?>
    <h4 class="center correct">Congratulations <?= $player ?>, you won!</h4>
    <form class="center guess" action="play" method="post">
        <input class="button" type="submit" name="do-reset" value="New game">
    </form>
<?php } else if ($status == "one" || $computer) { ?>
    <form class="center guess" action="play" method="post">
        <input class="button" type="submit" name="do-end" value="Next player">
        <input class="button" type="submit" name="do-reset" value="Reset">
    </form>
<?php } else if ($status == "play" || $status == "start") { ?>
    <br>
    <form class="center guess" action="play" method="post">
        <input class="button" type="submit" name="do-end" value="End the round">
        <input class="button" type="submit" name="do-roll" value="Roll the dice">
        <input class="button" type="submit" name="do-reset" value="Reset">
    </form>
<?php } ?>

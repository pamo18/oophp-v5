<?php

namespace Anax\View;

?>
<?php if ($numPlayers == null) { ?>
<h1 class="center">First you need to Setup the game</h1>

<form class="center guess" action="setup" method="post">
    <label for="players">Number of players</label>
    <input class="center stack" id="players" required min="2" type="number" name="num-players" value="2">
    <label for="dice">Number of dice</label>
    <input class="center stack" id="dice" required type="number" name="dice" value="1">
    <input class="button" type="submit" name="do-setup" value="Continue">
</form>
<?php } else { ?>
<h1 class="center">Now add your names</h1>

<form class="center guess" action="setup" method="post">
    <label for="player1">Player 1 name:</label>
    <input class="center wide" id="player1" required type="text" value="Computer" name="player-names[]">
    <?php
    for ($i = 2; $i <= $numPlayers; $i++) { ?>
        <label for="player<?=$i?>">Player <?=$i?> name:</label>
        <input class="center wide" id="player<?=$i?>" required type="text" name="player-names[]">
    <?php } ?>
    <input class="button" type="submit" name="do-init" value="Continue">
</form>
<?php } ?>

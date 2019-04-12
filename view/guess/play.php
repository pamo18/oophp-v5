<?php

namespace Anax\View;

/**
 * Render content within an article.
 */

// Show incoming variables and view helper functions
//echo showEnvironment(get_defined_vars(), get_defined_functions());
?>
<h1 class="center">Guess my number</h1>
<h4 class="center">Guess a number between 1 and 100</h4>
<h5 class="center"><?= ($tries ? $tries.' trie(s) remaining': 'Game over!') ?></h5>

<form class="center guess" action="game-guess" method="post">
    <input class="center stack" id="guess" type="number" name="guess">
    <input class="button" type="submit" name="do-guess" value="Guess">
</form>

<div class="game-controls">
    <form class="center" action="game-reset" method="post">
        <input class="button" type="submit" name="reset" value="Reset">
    </form>
    <form class="center" action="game-cheat" method="post">
        <input class="button" type="submit" name="cheat" value="Cheat">
    </form>
</div>
    <div class="result"><p class='center'><?= ($result ? $result : "Type a number and click Guess to start!")?></p></div>
<?= (isset($show) ? "<p class='center cheat'>You cheater!  The number is {$show}</p>" : null); ?>

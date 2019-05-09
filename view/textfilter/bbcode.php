<?php

namespace Anax\View;

?>

<h2>Source in bbcode.txt</h2>
<pre><?= wordwrap(htmlentities($text)) ?></pre>

<h2>Filter BBCode applied, source</h2>
<pre><?= wordwrap(htmlentities($html)) ?></pre>

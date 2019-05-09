<?php

namespace Anax\View;

?>

<h1>Showing off Markdown</h1>
<h2>Markdown source in sample.md</h2>
<pre><?= $text ?></pre>

<h2>Text formatted as HTML source</h2>
<pre><?= htmlentities($html) ?></pre>

<h2>Text displayed as HTML</h2>
<?= $html ?>

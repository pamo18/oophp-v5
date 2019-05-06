<?php

namespace Anax\View;

/**
 * Render content within an article.
 */

// Show incoming variables and view helper functions
//echo showEnvironment(get_defined_vars(), get_defined_functions());

?>
<hr>
<pre>
SESSION
<?php var_dump($_SESSION) ?>
POST
<?php var_dump($_POST) ?>
GET
<?php var_dump($_GET) ?>
</pre>
<hr>

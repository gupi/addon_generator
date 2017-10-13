<?php

/**
 *Addon Template.
 *
 * @author Gunter Pietzch
 *
 * @package redaxo5
 */

?>
<p>
Addon Template

<br /><br />

<a href="http://prestosoft.de/">zur Seite des Erstellers</a>

<br /><br />

<?php
    $file = dirname(__FILE__) . '/_changelog.txt';
    if (is_readable($file)) {
        echo str_replace('+', '&nbsp;&nbsp;+', nl2br(file_get_contents($file)));
    }
?>
</p>

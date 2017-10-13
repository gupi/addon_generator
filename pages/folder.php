<?php
/**
 * Addon Template.
 *
 * @author Gunter Pietzsch
 *
 * @package redaxo5
 *
 */
$pieces = array ();
$path = $this->getDataPath ();
$filename = "README.MD";
$d = dir ( $path );
$readme = str_replace("<br>", "\n", showXMLDescription ( $path, "", $filename ));
if ($readme) {
  
  $fragment = new rex_fragment ();
  $fragment->setVar ( 'title', $this->i18n ( 'template_info' ) );
  $fragment->setVar ( 'body', rex_string::highlight ( $readme ), false );
  $content = $fragment->parse ( 'core/page/section.php' );
  
  echo $content;
}

$pieces = array ();
$pieces [] = "<table class='table'>";
$pieces [] = "<thead><tr><th>" . $this->i18n ( 'template_folder' ) . "</th><th>" . $this->i18n ( 'template_table_description' ) . "</th></tr></thead>";

if ($d) {
  while ( ($entry = $d->read ()) !== FALSE ) {
    if ($entry != "." and $entry != "..") {
      if (is_dir($path."/".$entry."/")) {
        $pieces [] = "<tr>";
        $pieces [] = "<td>";
        $pieces [] = $entry;
        $pieces [] = "</td>";
        $pieces [] = "<td>";
        $pieces [] = showXMLDescription ( $path, $entry . "/", $filename );
        $pieces [] = "</td>";
        $pieces [] = "</tr>";
      }
    }
  }
  $d->close ();
}

$pieces [] = "</table>";

$content = join ( "\n", $pieces );

$fragment = new rex_fragment ();
$fragment->setVar ( 'title', $this->i18n ( 'template_folder_headline' ) );
$fragment->setVar ( 'body', $content, false );
$content = $fragment->parse ( 'core/page/section.php' );

echo $content;
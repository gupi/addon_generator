<?php
/**
 * Addon Template.
 *
 * @author Gunter Pietzsch
 *
 * @package redaxo5
 *
 */


$pieces = array();
$pieces[] = "<table class='table'>";
$pieces[] = "<thead><tr><th>".$this->i18n('template_files')."</th><th>".$this->i18n('template_table_description')."</th></tr></thead>";
$pieces[] = "<tr>";
$pieces[] = "<td>";
$pieces[] = "package.yml";
$pieces[] = "</td>";
$pieces[] = "<td>";
$pieces[] = $this->i18n('template_file_msg1');
$pieces[] = "</td>";
$pieces[] = "</tr>";
$pieces[] = "</table>";
$content = join("\n", $pieces);

$fragment = new rex_fragment();
$fragment->setVar('title', $this->i18n('template_required_files_headline'));
$fragment->setVar('body', $content, false);
$content = $fragment->parse('core/page/section.php');

echo $content;

$pieces = array();
$pieces[] = "<table class='table'>";
$pieces[] = "<thead><tr><th>".$this->i18n('template_folder')."</th><th>".$this->i18n('template_table_description')."</th></tr></thead>";
$pieces[] = "<tr>";
$pieces[] = "<td>";
$pieces[] = "boot.php";
$pieces[] = "</td>";
$pieces[] = "<td>";
$pieces[] = $this->i18n('template_file_msg2')."<a href='https://redaxo.org/doku/master/extension-points'>".$this->i18n('template_documentation')."</a> ";
$pieces[] = "</td>";
$pieces[] = "</tr>";
$pieces[] = "<tr>";
$pieces[] = "<td>";
$pieces[] = "help.php";
$pieces[] = "</td>";
$pieces[] = "<td>";
$pieces[] = $this->i18n('template_file_msg3');
$pieces[] = "</td>";
$pieces[] = "</tr>";
$pieces[] = "<tr>";
$pieces[] = "<td>";
$pieces[] = "install.php";
$pieces[] = "</td>";
$pieces[] = "<td>";
$pieces[] = $this->i18n('template_file_msg4');
$pieces[] = "</td>";
$pieces[] = "</tr>";
$pieces[] = "<tr>";
$pieces[] = "<td>";
$pieces[] = "install.sql";
$pieces[] = "</td>";
$pieces[] = "<td>";
$pieces[] = $this->i18n('template_file_msg5');
$pieces[] = "</td>";
$pieces[] = "</tr>";
$pieces[] = "<tr>";
$pieces[] = "<td>";
$pieces[] = "uninstall.sql";
$pieces[] = "</td>";
$pieces[] = "<td>";
$pieces[] = $this->i18n('template_file_msg6');
$pieces[] = "</td>";
$pieces[] = "</tr>";
$pieces[] = "</table>";

$content = join("\n", $pieces);

$fragment = new rex_fragment();
$fragment->setVar('title', $this->i18n('template_optional_files_headline'));
$fragment->setVar('body', $content, false);
$content = $fragment->parse('core/page/section.php');

echo $content;

$pieces = array();
$pieces[] = $this->i18n('template_file_msg0');
$pieces[] = "<a href='https://redaxo.org/doku/master/aenderungen-v4-v5#packages'>".$this->i18n('template_documentation')."</a>";
$content = join("\n", $pieces);

$fragment = new rex_fragment();
$fragment->setVar('title', $this->i18n('template_hint'));
//$fragment->setVar('body', rex_string::highlight($content), false);
$fragment->setVar('body', $content, false);
$content = $fragment->parse('core/page/section.php');

echo $content;

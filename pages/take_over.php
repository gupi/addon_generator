<?php
if (rex_post ( 'take_over', 'boolean' )) {
  $source = rex_post ( 'addon','string');
  
  $content = "<pre>".print_r(rex_addon::get($source),TRUE)."</pre>";
  $formElements = [ ];
  
  $n = [ ];
  $n ['field'] = '<button class="btn btn-save rex-form-aligned" type="submit" name="addon_selected" value="'.$source.'" ' . rex::getAccesskey ( $this->i18n ( 'template_continue' ), 'save' ) . '>' . $this->i18n ( 'template_continue' ) . '</button>';
  $formElements [] = $n;
  
  $fragment = new rex_fragment ();
  $fragment->setVar ( 'flush', true );
  $fragment->setVar ( 'elements', $formElements, false );
  $buttons = $fragment->parse ( 'core/form/submit.php' );
  
  $fragment = new rex_fragment ();
  $fragment->setVar ( 'class', 'edit' );
  $fragment->setVar ( 'title', "Addon" );
  $fragment->setVar ( 'body', $content, false );
  $fragment->setVar ( 'buttons', $buttons, false );
  $content1 = $fragment->parse ( 'core/page/section.php' );
  
} elseif(rex_post ( 'addon_selected', 'string' )) {
  
  $source = rex_post ( 'addon_selected', 'string' );
  $destination = "copy_of_".$source;
  $candidat = rex_addon::get($source);
  $page = $candidat->getProperty('page');
  $requires = $candidat->getProperty('requires');
  $pieces = [];
  $pieces[] = "<b>".$source."</b> wird als <b>".$destination."</b> kopiert<br/>";
  $pieces[] = "Pfad: ".rex_path::core();
//   $pieces[] = '<input class="form-control icp" type="text" name="icon" value="'.str_replace("rex-icon ", "", $page['icon']).'">';

//   $content = rex_view::success (join("<br />", $pieces) );
  $content = join("<br />",$pieces);
  $formElements = [ ];
  
  $n = [ ];
  $n ['field'] = '<button class="btn btn-save rex-form-aligned" type="submit" name="addon_continue" value="'.$source.'" ' . rex::getAccesskey ( $this->i18n ( 'template_continue' ), 'save' ) . '>' . $this->i18n ( 'template_continue' ) . '</button>';
  $formElements [] = $n;
  
  $fragment = new rex_fragment ();
  $fragment->setVar ( 'flush', true );
  $fragment->setVar ( 'elements', $formElements, false );
  $buttons = $fragment->parse ( 'core/form/submit.php' );

  $content = '<fieldset>';
  $formElements = [ ];
  
  $parameter = ["name","title","author","version","release","icon","supportpage","info","permission"];
  $label = ["Addon Name","Titel","Author","Version","REDAXO Release","Icon","Support Page","Info","Permission"];
  $values = [$destination,$page['title'],$candidat->getProperty('author'),$candidat->getProperty('version'),$requires['redaxo'],str_replace(array("rex-icon ","fa "),array("",""), $page['icon']),$candidat->getProperty('supportpage'),
            $candidat->getProperty('info'),$page['perm']];
  foreach($parameter as $k=>$p) {
    $n = [ ];
    $n ['label'] = '<label for="rex-template-'.$p.'"> '.$label[$k].'</label>';
    if ($p == "icon") {
      $n ['field'] = '<input class="form-control icp" type="text" id="rex-template-'.$p.'" name="'.$p.'" value="' . $values[$k] . '" />&nbsp&nbsp';
    } else {
      $n ['field'] = '<input class="form-control" type="text" id="rex-template-'.$p.'" name="'.$p.'" value="' . $values[$k] . '" />&nbsp&nbsp';
    }
    $formElements [] = $n;
  }
  $fragment = new rex_fragment ();
  $fragment->setVar ( 'elements', $formElements, false );
  $content .= $fragment->parse ( 'core/form/form.php' );
  
  $fragment = new rex_fragment ();
  $fragment->setVar ( 'class', 'edit' );
  $fragment->setVar ( 'title', $this->i18n ( 'template_settings' ) );
  $fragment->setVar ( 'body', $content, false );
  $fragment->setVar ( 'buttons', $buttons, false );
  $content1 = $fragment->parse ( 'core/page/section.php' );

//   echo '<form action="' . rex_url::currentBackendPage () . '" method="post">' . $content1 . '</form>';  
  
} else {

$pieces = array ();
$path = rex_path::addon($this->getPackageId());
$d = dir ( $path );

$pieces = array ();
$pieces [] = "<table class='table'>";
$pieces [] = "<thead><tr><th class='text-center'>" . $this->i18n ( 'template_select' ) . "</th><th>" . "Addon" . "</th></tr></thead>";

if ($d) {
  while ( ($entry = $d->read ()) !== FALSE ) {
    if ($entry != "." and $entry != "..") {
      if (is_dir($path."/".$entry."/")) {
        $pieces [] = "<tr>";
        $pieces [] = "<td class='text-center'>";
        $pieces [] = "<input type='radio' name='addon' value='".$entry."'>";
        $pieces [] = "</td>";
        $pieces [] = "<td>";
        $pieces [] = $entry;
        $pieces [] = "</td>";
        $pieces [] = "</tr>";
      }
    }
  }
  $d->close ();
}

$pieces [] = "</table>";

$content = join ( "\n", $pieces );

  $formElements = [ ];
  
  $n = [ ];
  $n ['field'] = '<button class="btn btn-save rex-form-aligned" type="submit" name="take_over" value="1" ' . rex::getAccesskey ( $this->i18n ( 'template_continue' ), 'save' ) . '>' . $this->i18n ( 'template_continue' ) . '</button>';
  $formElements [] = $n;
  
  $fragment = new rex_fragment ();
  $fragment->setVar ( 'flush', true );
  $fragment->setVar ( 'elements', $formElements, false );
  $buttons = $fragment->parse ( 'core/form/submit.php' );
  
  $fragment = new rex_fragment ();
  $fragment->setVar ( 'class', 'edit' );
  $fragment->setVar ( 'title', "Addons" );
  $fragment->setVar ( 'body', $content, false );
  $fragment->setVar ( 'buttons', $buttons, false );
  $content1 = $fragment->parse ( 'core/page/section.php' );
}  
  echo '
    <form action="' . rex_url::currentBackendPage () . '" method="post">
        ' . $content1 . '
    </form>';

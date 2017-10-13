<?php

/** @var rex_addon $this */
/* $search = array (
    "***name***",
    "***version***",
    "***author***",
    "***title***",
    "***release***",
    "***icon***" ,
    "***supportpage***" ,
    "***info***" ,
    "***permission***"  
);
$replace = array (
    $this->getConfig ( 'name' ),
    $this->getConfig ( 'version' ),
    $this->getConfig ( 'author' ),
    $this->getConfig ( 'title' ),
    $this->getConfig ( 'release' ),
    $this->getConfig ( 'icon' ),
    $this->getConfig ( 'supportpage' ),
    $this->getConfig ( 'info' ),
    $this->getConfig ( 'permission' ) 
); */
if (rex_post ( 'config-submit', 'boolean' )) {
  $this->setConfig ( rex_post ( 'config', [ 
      [ 
          'title',
          'string' 
      ],
      [ 
          'name',
          'string' 
      ],
      [ 
          'author',
          'string' 
      ],
      [ 
          'version',
          'string' 
      ],
      [ 
          'release',
          'string' 
      ],
      [ 
          'icon',
          'string' 
      ],
      [ 
          'supportpage',
          'string' 
      ],
      [ 
          'info',
          'string' 
      ],
      [ 
          'permission',
          'string' 
      ],
      [ 
          'boot_php',
          'bool' 
      ],
      [ 
          'help_php',
          'bool' 
      ],
      [ 
          'install_php',
          'bool' 
      ],
      [ 
          'install_sql',
          'bool' 
      ],
      [ 
          'uninstall_sql',
          'bool' 
      ],
      [ 
          'lang',
          'bool' 
      ],
      [ 
          'pages',
          'bool' 
      ],
      [ 
          'lib',
          'bool' 
      ],
      [ 
          'vendor',
          'bool' 
      ],
      [ 
          'functions',
          'bool' 
      ],
      [ 
          'module',
          'bool' 
      ],
      [ 
          'templates',
          'bool' 
      ],
      [ 
          'assets',
          'bool' 
      ],
      [ 
          'data',
          'bool' 
      ],
      [ 
          'scss',
          'bool' 
      ],
      [ 
          'install',
          'bool' 
      ],
      [ 
          'plugins',
          'bool' 
      ] 
  ] ) );
  
  if ($this->getConfig ( 'name' )) {
    $package = createTemplate($this);
    echo rex_view::success ( $this->i18n ( 'template_saved' ) );
    $formElements = [ ];
    
    $n = [ ];
    $n ['field'] = '<button class="btn btn-cancel rex-form-aligned" type="submit" name="config-cancel" value="1" ' . rex::getAccesskey ( $this->i18n ( 'template_cancel' ), 'save' ) . '>' . $this->i18n ( 'template_cancel' ) . '</button>';
    $formElements [] = $n;
    
    $n = [ ];
    $n ['field'] = '<button class="btn btn-save rex-form-aligned" type="submit" name="config-create" value="1" ' . rex::getAccesskey ( $this->i18n ( 'template_create' ), 'save' ) . '>' . $this->i18n ( 'template_create' ) . '</button>';
    $formElements [] = $n;
    
    $fragment = new rex_fragment ();
    $fragment->setVar ( 'flush', true );
    $fragment->setVar ( 'elements', $formElements, false );
    $buttons = $fragment->parse ( 'core/form/submit.php' );
    
    $fragment = new rex_fragment ();
    $fragment->setVar ( 'class', 'edit' );
    $fragment->setVar ( 'title', 'Addon' );
    $fragment->setVar ( 'body', $package->getHTMLInfo("summary"), false );
    $fragment->setVar ( 'buttons', $buttons, false );
    $content = $fragment->parse ( 'core/page/section.php' );
    
    echo '
    <form action="' . rex_url::currentBackendPage () . '" method="post">
        ' . $content . '
    </form>';
  } else {
    echo rex_view::warning ( $this->i18n ( 'template_name_missing' ) );
  }
} elseif (rex_post ( 'config-create', 'boolean' )) {
  
  $package = createTemplate($this);
  $pieces = array ();
  if ($package->is_active()) {
    $pieces [] = "<h3>Das Addon ist installiert - bitte de-installieren und löschen</h3>";
  } else {
    if (!$package->has_folder()) {
      if ($package->createPackageFolder()) {
        $pieces [] = "<h3>neues Addon-Verzeichnis: <b>" . $package->getPackageRoot() . "</b> wurde angelegt.</h3>";
        if ($package->createYmlFile()) {
          $pieces [] = "<b>folgende Dateien wurden bereitgestellt:</b><i>";
          $pieces [] = "package.yml";
        }
        if($package->has_optional_files()) {
          $files = $package->createPackageOptionalFiles();
          foreach ($files as $f) {
            $pieces[] = $f;
          }
        }
        if ($package->has_optional_folders()) {
          $pieces [] = "<b>Folgende Verzeichnisse wurden bereitgestellt:</b><i>";
          $folder = $package->createPackageOptionalSubFolders();
          foreach ($folder as $f) {
            $pieces[] = $f;
          }
        }
      } else {
        $pieces [] = "<h3>Das Verzeichnis konnte nicht angelegt werden!</h3>";
      }
    } else {
      $pieces [] = "<h3>".$package->getValue("name")." - Dieses Addon-Verzeichnis ist bereits vorhanden!</h3>";
      $pieces [] = "<h3>Bitte über das Addon-Menü löschen.</h3>";
    }
  }
  $pieces [] = "</i>";
  $content = join ( "<br>", $pieces );
  
  $fragment = new rex_fragment ();
  $fragment->setVar ( 'title', $this->i18n ( 'template_info' ) );
  $fragment->setVar ( 'body', $content, false );
  $content = $fragment->parse ( 'core/page/section.php' );
  
  echo $content;
} else {
  
  $content = '';
  $formElements = [ ];
  $parameter = ["name","title","author","version","release","icon","supportpage","info","permission"];
  $label = ["Addon Name","Titel","Author","Version","REDAXO Release","Icon","Support Page","Info","Permission"];
  foreach($parameter as $k=>$p) {
    $n = [ ];
    $n ['label'] = '<label for="rex-template-'.$p.'"> '.$label[$k].'</label>';
    if ($p == "icon") {
      $n ['field'] = '<input class="form-control icp" type="text" id="rex-template-'.$p.'" name="config['.$p.']" value="' . $this->getConfig ( $p ) . '" />&nbsp&nbsp';
    } else {
      $n ['field'] = '<input class="form-control" type="text" id="rex-template-'.$p.'" name="config['.$p.']" value="' . $this->getConfig ( $p ) . '" />&nbsp&nbsp';
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
  $content0 = $fragment->parse ( 'core/page/section.php' );
  
  $content = '';
  
  $formElements = [ ];
  $n = [ ];
  $n ['label'] = '<label for="rex-template-package_yml">package.yml</label>';
  $n ['field'] = '<input type="checkbox" id="rex-template-package_yml" name="config[package_yml]" value="1" checked="checked" disabled="disabled")  />&nbsp&nbsp';
  $formElements [] = $n;
  
  $parameter = ["boot_php","help_php","install_php","install_sql","uninstall_sql"];
  
  foreach($parameter as $p) {
    $n = [ ];
    $n ['label'] = '<label for="rex-template-'.$p.'">'.str_replace("_",".",$p).'</label>';
    $n ['field'] = '<input type="checkbox" id="rex-template-'.$p.'" name="config['.$p.']" value="1" ' . ($this->getConfig ( $p ) ? ' checked="checked"' : '') . ' />&nbsp&nbsp';
    $formElements [] = $n;
  }
  
  $fragment = new rex_fragment ();
  $fragment->setVar ( 'elements', $formElements, false );
  $content .= $fragment->parse ( 'core/form/checkbox.php' );
  
  $fragment = new rex_fragment ();
  $fragment->setVar ( 'class', 'edit' );
  $fragment->setVar ( 'title', $this->i18n ( 'template_files' ) );
  $fragment->setVar ( 'body', $content, false );
  $fragment->setVar ( 'buttons', $buttons, false );
  $content1 = $fragment->parse ( 'core/page/section.php' );
  
  $content = '';
  
  $formElements = [ ];

  $parameter = ["lang","pages","lib","vendor","functions","module","templates","assets","data","scss","plugins"];
  foreach($parameter as $p) {
    $n = [ ];
    $n ['label'] = '<label for="rex-template-'.$p.'">'.$p.'</label>';
    $n ['field'] = '<input type="checkbox" id="rex-template-'.$p.'" name="config['.$p.']" value="1" ' . ($this->getConfig ( $p ) ? ' checked="checked"' : '') . ' />&nbsp&nbsp';
    $formElements [] = $n;
  }
  
  $fragment = new rex_fragment ();
  $fragment->setVar ( 'elements', $formElements, false );
  $content .= $fragment->parse ( 'core/form/checkbox.php' );
  
  $formElements = [ ];
  
  $n = [ ];
  $n ['field'] = '<button class="btn btn-save rex-form-aligned" type="submit" name="config-submit" value="1" ' . rex::getAccesskey ( $this->i18n ( 'template_continue' ), 'save' ) . '>' . $this->i18n ( 'template_continue' ) . '</button>';
  $formElements [] = $n;
  
  $fragment = new rex_fragment ();
  $fragment->setVar ( 'flush', true );
  $fragment->setVar ( 'elements', $formElements, false );
  $buttons = $fragment->parse ( 'core/form/submit.php' );
  
  $fragment = new rex_fragment ();
  $fragment->setVar ( 'class', 'edit' );
  $fragment->setVar ( 'title', $this->i18n ( 'template_folder' ) );
  $fragment->setVar ( 'body', $content, false );
  $fragment->setVar ( 'buttons', $buttons, false );
  $content2 = $fragment->parse ( 'core/page/section.php' );
  
  echo '
    <form action="' . rex_url::currentBackendPage () . '" method="post">
        ' . $content0 . $content1 . $content2 . '
    </form>';
}
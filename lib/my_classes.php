<?php
class addonPackage {
  private $is_valid;
  private $values;
  private $dummies;
  private $package_root;
  private $data_source;
  
  function __construct() {
    $this->values = [ ];
    $this->values ['name'] = "";
    $this->values ['title'] = "";
    $this->values ['author'] = "";
    $this->values ['version'] = "";
    $this->values ['release'] = "";
    $this->values ['icon'] = "";
    $this->values ['supportpage'] = "";
    $this->values ['info'] = "";
    $this->values ['permission'] = "";
    $this->values ['files'] = [ ];
    $this->values ['folders'] = [ ];
    
    $this->is_valid = FALSE;
    $this->dummies = array (
        "***name***",
        "***title***",
        "***author***",
        "***version***",
        "***release***",
        "***icon***" ,
        "***supportpage***" ,
        "***info***" ,
        "***permission***"   
    );
  }
  function has_folder() {
    if (rex_dir::isWritable($this->getPackageRoot())) {
      return TRUE;
    }
    return FALSE;
  }
  function is_active() {
    if (rex_addon::exists($this->getValue("name"))) {
      return TRUE;
    }
    return FALSE;
  }
  function has_optional_folders() {
    if (count($this->getValue('folders'))) {
      return TRUE;
    }
    return FALSE;
  }
  function has_optional_files() {
    if (count($this->getValue('files'))) {
      return TRUE;
    }
    return FALSE;
  }
  function getReplacements(){
    return array($this->getValue("name"),$this->getValue("title"),$this->getValue("author"),$this->getValue("version"),$this->getValue("release"),$this->getValue("icon"),$this->getValue("supportpage"),$this->getValue("info"),$this->getValue("permission"));
  }
  function setDataSource($path) {
    $this->data_source = $path;
  }
  function getStatus() {
    return $this->is_valid;
  }
  function setStatus($new_status = FALSE) {
    $this->is_valid = $new_status;
    return $this->status;
  }
  function setValue($v_name, $value) {
    $this->values [$v_name] = $value;
    return $this->values [$v_name];
  }
  function getValue($v_name) {
    return $this->values [$v_name];
  }
  function initialise($values = []) {
    foreach ( $values as $k => $v ) {
      $this->setValue ( $k, $v );
    }
    if ($this->getValue ( "name" )) {
      $this->setStatus ( TRUE );
      $this->package_root = str_replace("//","/".$this->getValue("name")."/",rex_path::addon($this->getValue("name")));
    }
  }
  function addFile($filename) {
    if ($filename) {
      $this->values['files'][]=$filename;
    }
  }
  function addFolder($foldername) {
    if ($foldername) {
      $this->values['folders'][]=$foldername;
    }
  }
  function getPackageRoot() {
    return $this->package_root;
  }
  function createYmlFile() {
    $source = $this->data_source."package.yml";
    $destination = $this->package_root."package.yml";
    if (rex_file::copy($source,$destination)) {
      if(replaceDummies($destination, $this->dummies, $this->getReplacements())) {
        return TRUE;
      }
    }
    return FALSE;
  }
  function createPackageFolder() {
    if (rex_dir::create($this->getPackageRoot())) {
      return TRUE;
    }
    return FALSE;
  }
  function createPackageOptionalSubFolders() {
    $folders = [];
    foreach ($this->getValue('folders') as $v) {
      $msg = "";
      if (rex_dir::copy ( $this->data_source.$v."/", $this->package_root .$v. "/" )) {
        if ($v == "lang") {
          if(replaceDummies($this->package_root .$v. "/de_de.lang", $this->dummies, $this->getReplacements())) {
            $msg .= " - de_de.lang wurde aktualisiert";
          }
          if(replaceDummies($this->package_root .$v. "/en_gb.lang", $this->dummies, $this->getReplacements())){
            $msg .= " - en_gb.lang wurde aktualisiert";
          }
        }
        if ($v == "pages") {
          if(replaceDummies($this->package_root .$v. "/index.php", $this->dummies, $this->getReplacements())) {
            $msg .= " - index.php wurde aktualisiert";
          }
        }
        $folders [] = $v.$msg;
        $this->removeReadmeFile($this->package_root .$v. "/");
      }
    }
    return $folders;
  }
  function createPackageOptionalFiles() {
    $files = [];
    foreach ($this->getValue('files') as $v) {
      $source = $this->data_source.$v;
      $destination = $this->package_root.$v;
      if (rex_file::copy ( $source, $destination )) {
        $files [] = $v;
      }
    }
    return $files;
  }
  function removeReadmeFile($folder) {
    $file = $folder."README.MD";
    if (rex_file::delete($file)) {
      return TRUE;
    }
    return FALSE;
  }
  function createFileHeader($filename) {
  }
  function getHTMLInfo($type) {
    $pieces = [];
    switch ($type) {
      case ("summary"):
        $pieces[]= '<h3>Addon-Name: <b><i>' . $this->getValue ( 'name' ) . '</i></b><br></h3>
        <h3>Titel: <b><i>' . $this->getValue ( 'title' ) . '</i></b><br></h3>
        <h3>Author: <b><i>' . $this->getValue  ( 'author' ) . '</i></b><br></h3>
        <h3>Version: <b><i>' . $this->getValue  ( 'version' ) . '</i></b><br></h3>
        <h3>REDAXO Version: <b><i>' . $this->getValue  ( 'release' ) . '</i></b><br></h3>
        Folgende Dateien werden bereitgestellt:<br><b><i>package.yml</b></i><br>';
        foreach ($this->getValue("files") as $k=>$v) {
          $pieces[] = "<b><i>".$v."</i></b><br />"; 
        }
        $pieces[] = "<br>Folgende Verzeichnisse werden angelegt:<br>";
        foreach ($this->getValue("folders") as $k=>$v) {
          $pieces[] = "<b><i>".$v.$this->addRemark($v)."</i></b><br />"; 
        }
        break;
      case ("filetable"):
        
        break;
      case ("foldertable"):
        
        break;
        default:
          $pieces[] = "<pre>";
          $pieces[] = print_r($this,true);
          $pieces[] = "Has Folder: ".($this->has_folder()?"Ja":"Nein");
          $pieces[] = "Has Sub-Folder: ".($this->has_optional_folders() ?"Ja":"Nein");
          $pieces[] = "Has Optional Files: ".($this->has_optional_files() ?"Ja":"Nein");
          $pieces[] = "Exists: ".($this->is_active()  ?"Ja":"Nein");
          
          $pieces[] = "</pre>";
          break;
        
    }
    return join("\n", $pieces);
  }
  function addRemark($field) {
    switch ($field) {
      case ("lang"):
        $remark = "</b> mit <b>de_de.lang</b> und <b>en_gb.lang</b>";
        break;
      case ("pages"):
        $remark = "</b> mit <b>index.php</b>";
        break;
      default:
        $remark = "";
        break;
    }
    return $remark;
  }
}

 
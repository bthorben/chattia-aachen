<?php

include_once("readFolder.php");
 
class ReadDownload extends ReadFolder {

  public $a = 0;

  function read($folderpath) {
    parent::read($folderpath, false);
  }

  function readContent($folder) {
    global $a;
  
    $name = basename($folder);
    $name = substr($name, 0);
  
    if(is_dir($folder)) { //Ordner
      $number = substr($name, 0, 1);
      $header = substr($name, 4);
      if($number != ($a-1)) {
        echo "</div><div class=\"col-md-4\">";
        $a++;
      }
    
      echo "<h4>".$header."</h4>";
      $this->read($folder);
    } else { //Datei     
    
      $name = strstr($name , '.', true);
      echo "<li><a href=\"".$folder."\">".$name."</a></li>";
    }
  }

}

?>

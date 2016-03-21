<?php

  abstract class ReadFolder {
    
    abstract function readContent($folder);
    
    function read($folderpath='', $reverse=true) {
      $folder = $folderpath;
            
      //Check if Path is a Folder
      if(!is_dir($folder)) {
        echo "CritError: Ungueltiger Ordnername";
        echo "</br>";
        return;
      }
      
      //Opens folder and read every item
      $dh = opendir($folder);
      while (false !== ($filename = readdir($dh))) {
        $allfiles[] = $filename;
      }
      if($reverse) {
        rsort($allfiles);
      } else {
        sort($allfiles);
      }
      
      foreach($allfiles as $file) {
        if($file != "." && $file != ".." && $file != "_thumbnail") {
          $completePath = $folder."/".$file;
          $this->readContent($completePath);
        }
      
      }    
      
      closedir($dh);
    
    }
       
  }
  
?>

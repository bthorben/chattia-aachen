<?php
  include_once("thumbnail.php");
  
  $b = 0;

  function readPicFolder($folderpath) {
    global $b;
    $ordner = $folderpath;
    
    echo "<div class=\"row tan\">";    
    echo "<h2>".basename($ordner)."</h2>";
    
    //Check if Path is a Folder
    if(!is_dir($ordner)) {
      echo "Ungueltiger Ordnername";
      echo "</br>";
      return;
    }
   
    //Opens folder and read every item
    $dh = opendir($ordner);
    while (false !== ($filename = readdir($dh))) {
      $files[] = $filename;
    }
    sort($files);
          
    $allebilder = $files;  
   

    foreach($allebilder as $bild) {
      $bildinfo = pathinfo($ordner."/".$bild);
      $string = explode('.', $bildinfo['basename']); 
      $endung = $string[count($string)-1];
      if($endung == "JPG" || $endung == "PNG") {
        make_thumbnail($bildinfo['dirname']."/".$bildinfo['basename'], "img/_thumbnail/".$bildinfo['basename']);
        
        $thumbnailpath = "img/_thumbnail/".$bildinfo['basename'];
        $picpath = $bildinfo['dirname']."/".$bildinfo['basename'];
        
        $picname = $bildinfo['basename'];
        $picname = substr($picname, 0, -4);
      
        echo "<img class=\"galerie_pics\" src=\"".$thumbnailpath."\" data-glisse-big=\"".$picpath."\" rel=\"group".$b."\" title=\"".$picname."\"/>";
      }
    }
    echo "</br></br>";
    echo "</div>";
    closedir($dh);
    $b++;
  }
    
?>

<?php
  include_once("thumbnail.php");
  include_once("readFolder.php");
  
  class ReadPicFolder extends ReadFolder {
  
  public $b = 0;
  
  function readContent($folderpath) {
    global $b;
    $ordner = $folderpath;
    
    $title = basename($ordner);
    $title = substr($title, 11);
    
    echo "<div class=\"row tan\">";    
    echo "<h2>".$title."</h2>";
    
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
      
      if (!file_exists('img/_thumbnail/'.basename($ordner))) {
        mkdir('img/_thumbnail/'.basename($ordner), 0777, true);
      }
      
      if(strcasecmp($endung, "jpg") == 0 || strcasecmp($endung, "PNG") == 0) {
        make_thumbnail($bildinfo['dirname']."/".$bildinfo['basename'], "img/_thumbnail/".basename($ordner)."/".$bildinfo['basename']);
        
        $thumbnailpath = "img/_thumbnail/".basename($ordner)."/".$bildinfo['basename'];
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
  
}
    
?>

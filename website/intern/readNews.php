<?php

include_once("readFolder.php");

function inputThumbnailText($filepath) {
  $news = fopen($filepath, "r");
  if($news == NULL) {
    echo "Datei konnte nicht geoeffnet werden";
    echo "<br/>";
    return;
  }
  
  $line = chop(fgets($news));
  
  return $line;
  
  fclose($news);
}

function inputWholeText($filepath) {
  $news = fopen($filepath, "r");
  if($news == NULL) {
    echo "Datei konnte nicht geoeffnet werden";
    echo "<br/>";
    return;
  }
  
  $result = "";  
  while(!feof($news)) {
    $line = chop(fgets($news));
    $result = $result.$line."<br/>";
  }
  
  return $result;
  fclose($news);
}

class ReadNews extends ReadFolder {

public $a=0;

function readContent($filepath) {
  global $a;
  $header = basename($filepath);
  $header = substr($header, 11, -4);
  
  echo "<div class=\"row beige\">
        <a name=\"".$header."\"></a>
        <h2>".$header."</h2>
        <p class=\"text-justify\" id=\"readMoreButton".$a."\">     
          ".inputThumbnailText($filepath)."       
        <a href=\"#".$header."\" onclick=\"readMore('#readMoreButton".$a."', '#more".$a."');\">Weiterlesen...</a>
        </p>
        <p class=\"text-justify\" id=\"more".$a."\">
          <script>ausblendenWithoutAnimation('#more".$a."');</script>
          ".inputWholeText($filepath)."
          <a href=\"#".$header."\" onclick=\"readMore('#more".$a."', '#readMoreButton".$a."');\">weniger zeigen ...</a>
        </p>
      </div>";
      
  $a++;
}
}
        
        


?>

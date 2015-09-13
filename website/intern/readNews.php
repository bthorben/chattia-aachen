<?php

$a=0;

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

function showNews($filepath) {
  global $a;
  $header = basename($filepath);
  $header = substr($header, 0, -4);
  
  echo "<div class=\"row beige\">
        <a name=\"".$header."\"></a>
        <h2>".$header."</h2>
        <p class=\"text-justify\" id=\"readMoreButton".$a."\">     
          ".inputThumbnailText($filepath)."       
        <a href=\"#".$header."\" onclick=\"readMore('#readMoreButton".$a."', '#more".$a."');\">Read More</a>
        </p>
        <p class=\"text-justify\" id=\"more".$a."\">
          <script>ausblendenWithoutAnimation('#more".$a."');</script>
          ".inputWholeText($filepath)."
          <a href=\"#".$header."\" onclick=\"readMore('#more".$a."', '#readMoreButton".$a."');\">Read Less</a>
        </p>
      </div>";
      
  $a++;
}
        
        


?>

<?php   
    function returnCalendarDates($datei, $count) {
      $dateTransEnDe = array(
        'Monday'    => 'Montag',
        'Tuesday'   => 'Dienstag',
        'Wednesday' => 'Mittwoch',
        'Thursday'  => 'Donnerstag',
        'Friday'    => 'Freitag',
        'Saturday'  => 'Samstag',
        'Sunday'    => 'Sonntag',
        'Mon'       => 'Mo',
        'Tue'       => 'Di',
        'Wed'       => 'Mi',
        'Thu'       => 'Do',
        'Fri'       => 'Fr',
        'Sat'       => 'Sa',
        'Sun'       => 'So',
        'January'   => 'Januar',
        'February'  => 'Februar',
        'March'     => 'März',
        'May'       => 'Mai',
        'June'      => 'Juni',
        'July'      => 'Juli',
        'October'   => 'Oktober',
        'December'  => 'Dezember',
      );
            
      $calendar = @fopen($path = $_SERVER['DOCUMENT_ROOT'] . $datei, "r");
      
      if($calendar == NULL) {
        echo "Kalendar konnte nicht geoeffnet werden";
        echo "<br/>";
        echo "<br/>";
        return;
      }
      
      $termine = array();
      
      while(!feof($calendar)) {
        $line = chop(fgets($calendar));
        
        $begin = "BEGIN:VEVENT";
        $end = "END:VEVENT";
        if(strcmp($line, $begin) == 0) {
          
          
          
          while(strcmp($line, $end) != 0) {
            $line = chop(fgets($calendar));
            if(preg_match("/^DTEND;/", $line)) { //Gibt Endzeit zurück
              if(preg_match("/VALUE=DATE/", $line)) {
                $endtime = substr($line, 17);
              } else {
                $endtime = substr($line, 37);
              }
              
              continue;
            }
            if(preg_match("/^DTSTART;/", $line)) { //Gibt Startzeit zurück
              if(preg_match("/VALUE=DATE/", $line)) {
                $starttime = substr($line, 19);
                $wholeday = 1;
              } else {
                $starttime = substr($line, 39);
                $wholeday = 0;
              }
              
              continue;
            }
            if(preg_match("/^LOCATION:/", $line)) { //Gibt Ort der Veranstaltung zurück
              $location = substr($line, 9);

              continue;
            }
            if(preg_match("/^SUMMARY;/", $line)) { //Gibt Beschreibung der Veranstaltung zurück
              $description = substr($line, 20);

              continue;
            }
          }
          
          $date = getdate();
          
          $month = str_pad($date["mon"], 2, '0', STR_PAD_LEFT);
          $day = str_pad($date["mday"], 2, '0', STR_PAD_LEFT);
          
          $convertedDate = $date["year"].$month.$day;
          
          //$convertedDate = "20150430";
          
          if($starttime > $convertedDate) {

            
            
            
            if($wholeday == 1) { //Ganztägiges Ereignis
              $starttimeAnzeige = date('D, j. F Y', mktime(0, 0, 0, substr($starttime, 4, 2), substr($starttime, 6, 2), substr($starttime, 0, 4)));
              $endtimeAnzeige = date('D, j. F Y', mktime(0, 0, 0, substr($endtime, 4, 2), substr($endtime, 6, 2), substr($endtime, 0, 4)));
            } else { //Ereignis mit Start- und Endzeit
              $starttimeAnzeige = date('D, j. F Y - H:i', mktime(substr($starttime, 9, 2), substr($starttime, 11, 2), substr($starttime, 13, 2), substr($starttime, 4, 2), substr($starttime, 6, 2), substr($starttime, 0, 4)));
              $endtimeAnzeige = date('D, j. F Y - H:i', mktime(substr($endtime, 9, 2), substr($endtime, 11, 2), substr($endtime, 13, 2), substr($endtime, 4, 2), substr($endtime, 6, 2), substr($endtime, 0, 4)));
            }
            

            //Umwandlung in Deutsch
            $starttimeAnzeige = strtr($starttimeAnzeige, $dateTransEnDe); 
            $endtimeAnzeige = strtr($endtimeAnzeige, $dateTransEnDe);
            
            $termine[] = array('Timestamp' => $starttime,
                               'Startzeit' => $starttimeAnzeige, 
                               'Endzeit' => $endtimeAnzeige,
                               'Beschreibung' => $description, 
                               'Ort' => $location);
            

            
            
          }
        }
        else {
          continue;
        }
      }
      fclose($calendar); 
      
      if(empty($termine)){
        echo "Es sind keine austehenden Termine bekannt";
        echo "<br/>";
        echo "<br/>";
        return;
      }
      
      //Sortiere Termine aufsteingend nach Startzeit
      foreach ($termine as $nr => $inhalt) {
        $start[$nr] = strtolower( $inhalt['Timestamp'] );
        $startA[$nr]  = strtolower( $inhalt['Startzeit'] );
        $endA[$nr]   = strtolower( $inhalt['Endzeit'] );
        $desc[$nr] = strtolower( $inhalt['Beschreibung'] );
        $loc[$nr] = strtolower( $inhalt['Ort'] );
      }    
      array_multisort($start, SORT_ASC, $termine);
            
      for($i = 0; $i < $count && $i < count($termine); $i++) {
        //echo $termine[$i]['Beschreibung'];
        //echo "<br/>";
        //echo $termine[$i]['Startzeit'];
        //echo "<br/>";
        //echo "Ort: ".$termine[$i]['Ort'];
        //echo "<br/>";
        //echo "Ende: ".$termine[$i]['Endzeit'];
        //echo "<br/>";
        //echo "<br/>";
        //echo "<br/>";
        
        echo "<div class=\"well well-sm\">";
          echo $termine[$i]['Beschreibung']." ";
          echo $termine[$i]['Startzeit'];
          echo "<br/>";
          echo "Ort: ".$termine[$i]['Ort'];
          echo "<br/>";
        echo "</div>";
      }
        
    }
    
?>

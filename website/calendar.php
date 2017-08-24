<?php
    //Wandelt tägliche und wöchentliche Serientermine um
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
            if(preg_match("/^SUMMARY;/", $line) || preg_match("/^SUMMARY:/", $line)) { //Gibt Beschreibung der Veranstaltung zurück
              $description = substr($line, (strpos($line, ':') + 1));

              continue;
            }
            if(preg_match("/^DESCRIPTION:/", $line)) { //Gibt Fließtext der Veranstaltung zurück
              $text = substr($line, 12);

              continue;
            }
            
            if(preg_match("/^RRULE:/", $line)) { //Serientermine
              $rrule_freq = substr($line, 11);
              $rrule_freq = strstr($rrule_freq , ';', true);
              
              $rrule_interval = substr(strstr($line, 'INTERVAL='), 6);
              $rrule_interval = strstr($rrule_interval, ';', true);
              
              $rrule_count = substr(strstr($line, 'COUNT='), 6);
              $rrule_count = strstr($rrule_count, ';', true);
              
              $rrule_until = substr(strstr($line, 'UNTIL='), 6);
              $rrule_until = strstr($rrule_until, ';', true);
              
              $rrule_recurDay = '';
              $rrule_recurMonthDay = '';
              
              
              switch($rrule_freq) {
                case "DAILY":
                  break;
                case "WEEKLY":
                  $rrule_recurDay = substr(strstr($line, 'BYDAY='), 6);
                  break;
                case "MONTHLY":
                  $rrule_recurMonthDay = substr(strstr($line, 'BYMONTHDAY='), 6);
                  $rrule_recurDay = substr(strstr($line, 'BYDAY='), 6);
                  echo "Error: Monatliche Termine sind nicht erlaubt";
                  echo "<br/>";
                  echo "<br/>";
                  break;
                case "YEARLY":
                  echo "Error: Jährlicher Termine sind nicht erlaubt";
                  echo "<br/>";
                  echo "<br/>";
                  break;
              }
              
                            
              continue;
            }
            
          }
          
          $date = getdate();
          //$date = getdate(mktime(0,0,0,11,10,2015));
          
          $month = str_pad($date["mon"], 2, '0', STR_PAD_LEFT);
          $day = str_pad($date["mday"], 2, '0', STR_PAD_LEFT);
          
          $convertedDate = $date["year"].$month.$day;         
                    
          if($endtime > $convertedDate) {
            
            /*
            $starttimeAnzeige = convertStartDate($starttime, $wholeday);
            $endtimeAnzeige = convertEndDate($endtime, $wholeday);

            //Umwandlung in Deutsch
            $starttimeAnzeige = strtr($starttimeAnzeige, $dateTransEnDe); 
            $endtimeAnzeige = strtr($endtimeAnzeige, $dateTransEnDe);
            */
            
            $termine[] = array('Startzeit' => strtotime($starttime),
                               'Endzeit' => strtotime($endtime),
                               'Beschreibung' => $description,
                               'Text' => $text,  
                               'Ort' => $location,
                               'Ganztaegig' => $wholeday);           
          }
          
          if(!empty($rrule_freq)) {
            
            //Setze Interval default auf 1
            if(empty($rrule_interval)) {
              $rrule_interval = 1;
            }
            /*
            
            echo "Starttime ".$starttimeAnzeige;
            echo "<br/>";
            echo "Endtime ".$endtimeAnzeige;
            echo "<br/>";
            echo "Description ".$description;
            echo "<br/>";
            echo "Freq ".$rrule_freq;
            echo "<br/>";
            echo "Interval ".$rrule_interval;
            echo "<br/>";
            echo "Count ".$rrule_count;
            echo "<br/>";
            echo "Until ".$rrule_until;
            echo "<br/>";
            echo "RecurDay ".$rrule_recurDay;
            echo "<br/>";
            echo "RecurMonthDay ".$rrule_recurMonthDay;
            echo "<br/>";
            echo "<br/>";
            
            */
            
            if(strcmp($rrule_freq, "DAILY") == 0) { //Tägliches Intervall
              if(empty($rrule_until)) { //Count gegeben
                for($i = 1; $i < $rrule_count; $i++) {
                  $termine[] = array('Startzeit' => (strtotime($starttime)+$rrule_interval*$i*24*3600),
                               'Endzeit' => (strtotime($endtime)+$rrule_interval*$i*24*3600),
                               'Beschreibung' => $description, 
                               'Text' => $text, 
                               'Ort' => $location,
                               'Ganztaegig' => $wholeday);    
                }
              } else { //Until gegeben
                for($i = 1; (strtotime($starttime)+$rrule_interval*$i*24*3600) < (strtotime($until)); $i++) {
                  $termine[] = array('Startzeit' => (strtotime($starttime)+$rrule_interval*$i*24*3600),
                                'Endzeit' => (strtotime($endtime)+$rrule_interval*$i*24*3600),
                                'Beschreibung' => $description, 
                                'Text' => $text, 
                                'Ort' => $location,
                                'Ganztaegig' => $wholeday); 
                } 
              }
            }
             
            if(strcmp($rrule_freq, "WEEKLY") == 0) { //Wöchentliches Intervall
              if(empty($rrule_until)) { //Count gegeben
                for($i = 1; $i < $rrule_count; $i++) {
                  $termine[] = array('Startzeit' => (strtotime($starttime)+$rrule_interval*$i*7*24*3600),
                               'Endzeit' => (strtotime($endtime)+$rrule_interval*$i*7*24*3600),
                               'Beschreibung' => $description, 
                               'Text' => $text, 
                               'Ort' => $location,
                               'Ganztaegig' => $wholeday);    
                }
              } else { //Until gegeben
                for($i = 1; (strtotime($starttime)+$rrule_interval*$i*7*24*3600) < (strtotime($until)); $i++) {
                  $termine[] = array('Startzeit' => (strtotime($starttime)+$rrule_interval*$i*7*24*3600),
                                'Endzeit' => (strtotime($endtime)+$rrule_interval*$i*7*24*3600),
                                'Beschreibung' => $description,
                                'Text' => $text,  
                                'Ort' => $location,
                                'Ganztaegig' => $wholeday); 
                } 
              }
            }
            
            $rrule_freq='';
          }
          
          
        }
        else {
          continue;
        }
      }
      fclose($calendar); 
            
      //Sortiere Termine aufsteigend nach Startzeit
      foreach ($termine as $nr => $inhalt) {
        $start[$nr] = strtolower( $inhalt['Startzeit'] );
        $end[$nr]   = strtolower( $inhalt['Endzeit'] );
        $desc[$nr] = strtolower( $inhalt['Beschreibung'] );
        $txt[$nr] = strtolower( $inhalt['Text'] );
        $loc[$nr] = strtolower( $inhalt['Ort'] );
        $ganztae[$nr] = strtolower( $inhalt['Ganztaegig'] );
      }    
      array_multisort($start, SORT_ASC, $termine);
      
      //Lösche vergangene Serientermine
      for($i = 0; $i < count($termine); $i++) {
        if(date('Ymd', $termine[$i]['Endzeit'])+1 < $convertedDate) {
          array_shift($termine);  
          $i--;
        }      

      }
      
      if(empty($termine)){
        echo "Es sind keine austehenden Termine bekannt";
        echo "<br/>";
        echo "<br/>";
        return;
      }
      

      for($i = 0; $i < $count && $i < count($termine); $i++) {
	      
	      //var_dump( $termine);
	      
	      /*
	      if(count($termine) > 0) {
	        while(date('Ymd', $termine[$i]['Endzeit'])+1 < $convertedDate) {
	          if(count($termine) > 0) {
	            array_shift($termine); 
	          } else {
	            break;
	          }
	          
	        }
	      }
	      */
        
        /*
        echo $termine[$i]['Beschreibung'];
        echo "<br/>";
        echo $termine[$i]['Startzeit'];
        echo "<br/>";
        echo "Ort: ".$termine[$i]['Ort'];
        echo "<br/>";
        echo "Ende: ".$termine[$i]['Endzeit'];
        echo "<br/>";
        echo "<br/>";
        echo "<br/>";
        */
        
                     
        if($termine[$i]['Ganztaegig']) {
          $starttimeAnzeige = date('D, j. F Y', $termine[$i]['Startzeit']);
          $endtimeAnzeige = date('D, j. F Y', $termine[$i]['Endzeit']-1);
        } else {
          $starttimeAnzeige = date('D, j. F - H:i', $termine[$i]['Startzeit']);
          $endtimeAnzeige = date('D, j. F - H:i', $termine[$i]['Endzeit']);
        }
        //Umwandlung in Deutsch
        $starttimeAnzeige = strtr($starttimeAnzeige, $dateTransEnDe); 
        $endtimeAnzeige = strtr($endtimeAnzeige, $dateTransEnDe);
        
        
        echo "<div class=\"well well-sm\">";
          echo $termine[$i]['Beschreibung']." ";
          //echo $termine[$i]['Startzeit'];
          echo $starttimeAnzeige;
          
          //Zeige Enddatum bei mehrtägigem Ereignis an
          if($termine[$i]['Ganztaegig'] == 1 && ($termine[$i]['Startzeit'] != $termine[$i]['Endzeit'])) {
            //echo " - ".$termine[$i]['Endzeit']; 
            echo " - ".$endtimeAnzeige;   
          }
          
          echo "<br/>";
          echo "Ort: ".$termine[$i]['Ort'];
          echo "<br/>";

          
          //echo $termine[$i]['Text'];
          
        echo "</div>";
      }
        
    }
    
?>

<?php  
  $station = htmlspecialchars($_GET["station"]);
  //$station = 'Fishponds';
  $loc_name = str_replace(' ', '_', $station);
  $file_name = strtolower($loc_name).'_no2.xml';
  $reader = new XMLReader();
  $reader->open($file_name);
 
  $year = '2016';
  $data = array();

  $day = 1; 
  while($reader->read()){
    if($reader->nodeType === XMLREADER::ELEMENT && $reader->localName === 'reading'){
      $date = $reader->getAttribute('date');
      if($reader->getAttribute('time') === '08:00:00' && substr($date, 6, 4) === $year){
        $reading = array();
        //$reading['date'] = $date;
        $dateForSort = str_replace('/', '-', $date);
        $reading['dateForSort'] = date('Y-m-d', strtotime($dateForSort));
        $reading['date'] = $date;
        $reading['val'] = (int)$reader->getAttribute('val');
        $data[] = $reading;
        $day++;
      }
    }
  }

  // foreach ($data as $row){
  //   echo '<p>date: '.$row['date'].'<br/>';
  //   echo 'val: '.$row['val']. '</p>';
  // }
  asort($data);

  $array = array();
  $array['cols'][] = array('label' => 'Day', 'type' => 'string');
  $array['cols'][] = array('label' => 'NO2 Concentration (µg/m³)', 'type' => 'number');
  $array['cols'][] = array('type' => 'string', 'role' => 'style', 'p' => array('role' => 'style'));
  //$array['cols'][] = array(array('label' => 'Day', 'type' => 'string'), array('label' => 'NO2', 'type' => 'number'), array('type' => 'string', 'role' => 'style', 'p' => array('role' => 'style')));

  foreach($data as $row){
    //$array['rows'][] = array('c' => array(array('v'=>$row['date']), array('v'=>$row['val'])) );
    $colorcode = null;

    if($row['val'] < 0 || $row['val'] >= 0 && $row['val']  <= 67){
      $colorcode = 'rgb(156, 255, 156)';
    }else if($row['val'] >= 68 && $row['val']  <= 134){
      $colorcode = 'rgb(49, 255, 0)';
    }else if($row['val'] >= 135 && $row['val']  <= 200){
      $colorcode = 'rgb(49, 207, 0)';
    }else if($row['val'] >= 201 && $row['val']  <= 267){
      $colorcode = 'rgb(255, 255, 0)';
    }else if($row['val'] >= 268 && $row['val']  <= 334){
      $colorcode = 'rgb(255, 207, 0)';
    }else if($row['val'] >= 335 && $row['val']  <= 400){
      $colorcode = 'rgb(255, 154, 0)';
    }else if($row['val'] >= 401 && $row['val']  <= 467){
      $colorcode = 'rgb(255, 100, 100)';
    }else if($row['val'] >= 468 && $row['val']  <= 534){
      $colorcode = 'rgb(255, 0, 0)';
    }else if($row['val'] >= 535 && $row['val']  <= 600){
      $colorcode = 'rgb(153, 0, 0)';
    }else if($row['val'] >= 601){
      $colorcode = 'rgb(206, 48, 255)';
    }

    //arranging the data into a data stucture that the Google chart could understand
    $array['rows'][] = array('c' => array(array('v'=>$row['date']), array('v'=>$row['val']), array('v'=>'point {fill-color:' . $colorcode)));
  }

  //echo json_encode($array);

  print json_encode($array);

?>
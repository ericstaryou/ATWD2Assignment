<?php 
  $date = htmlspecialchars($_GET["date"]);

  $station = htmlspecialchars($_GET["station"]);
  $loc_name = str_replace(' ', '_', $station);
  $file_name = strtolower($loc_name).'_no2.xml';

  $reader = new XMLReader();
  $reader->open($file_name);

  $data = array();

  //query time and val according to date
  while($reader->read()){
  	if($reader->nodeType === XMLREADER::ELEMENT && $reader->localName === 'reading'){
		if($reader->getAttribute('date') === $date){
			$reading = array();
			$time = $reader->getAttribute('time');
			$hour = (int)substr($time, 0, 2); //storing only the hour part of the time string

			$reading['time'] = $hour;
			$reading['val'] = (int)$reader->getAttribute('val');
			$data[] = $reading;
		}
	}
  }

 $hour_val = array();

 for ($i=0; $i < 24; $i++) { 
 	$hour_val[$i] = calAvgValue($data, $i);
 }

 //calculate average NO2 value for abitrary hour
 function calAvgValue(&$data, $hour){
	 $occurrence = null;
	 $value = null;
	 foreach ($data as $row) {
		 if($row['time'] == $hour){
			 if($value == null){
				 $value = $row['val'];
			 }else{
				 $value = $value + $row['val'];
			 }
			 $occurrence++;
		 }
	 }

	 //calc average NO2 value based on number of record
	 if($occurrence == 0){
		 return 0; 
	 }
	 $avg = $value/$occurrence;

	 return $avg;
 }

 $array = array();
 $array['cols'][] = array('label' => 'Hour', 'type' => 'number');
 $array['cols'][] = array('label' => 'NO2 Concentration (µg/m³)', 'type' => 'number');
 $array['cols'][] = array('type' => 'string', 'role' => 'style', 'p' => array('role' => 'style'));

 foreach($hour_val as $hour => $val){
    $colorcode = null;
    //colour encoding for different level of NO2 concentration
    if($val < 0 || $val >= 0 && $val  <= 67){
      $colorcode = 'rgb(156, 255, 156)';
    }else if($val >= 68 && $val  <= 134){
      $colorcode = 'rgb(49, 255, 0)';
    }else if($val >= 135 && $val  <= 200){
      $colorcode = 'rgb(49, 207, 0)';
    }else if($val >= 201 && $val  <= 267){
      $colorcode = 'rgb(255, 255, 0)';
    }else if($val >= 268 && $val <= 334){
      $colorcode = 'rgb(255, 207, 0)';
    }else if($val >= 335 && $val <= 400){
      $colorcode = 'rgb(255, 154, 0)';
    }else if($val >= 401 && $val  <= 467){
      $colorcode = 'rgb(255, 100, 100)';
    }else if($val >= 468 && $val  <= 534){
      $colorcode = 'rgb(255, 0, 0)';
    }else if($val >= 535 && $val  <= 600){
      $colorcode = 'rgb(153, 0, 0)';
    }else if($val >= 601){
      $colorcode = 'rgb(206, 48, 255)';
    }
	 
    //arranging the data into a data stucture that the Google chart could understand 
    //ref: http://stackoverflow.com/questions/17245478/building-array-and-formatting-json-for-google-charting-api
    $array['rows'][] = array('c' => array( array('v'=>$hour+1), array('v'=>$val),array('v'=>'point {size: 5;fill-color:'. $colorcode)) );
  }

  print json_encode($array);

?>

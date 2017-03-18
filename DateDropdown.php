<?php 
	$station = htmlspecialchars($_GET["station"]);
	//$station = "Fishponds";
  	$loc_name = str_replace(' ', '_', $station);
  	$file_name = strtolower($loc_name).'_no2.xml';
	
	$reader = new XMLReader();
  	$reader->open($file_name);

  	$data = array();

  	while($reader->read()){
  		if($reader->nodeType === XMLREADER::ELEMENT && $reader->localName === 'reading'){
  			$reading = array();
  			//array_push($data, $reader->getAttribute('date'));
  			$date = $reader->getAttribute('date');
  			$dateForSort = str_replace('/', '-', $date);
        	$reading['sortdate'] = date('Y-m-d', strtotime($dateForSort));
  			$reading['date'] = $date;
  			$data[] = $reading;
  		}
  	}

  	//remove duplicate values from a multi-dimensional array adopted from: 
  	//http://stackoverflow.com/questions/307674/how-to-remove-duplicate-values-from-a-multi-dimensional-array-in-php
  	$data = array_map("unserialize", array_unique(array_map("serialize", $data)));

  	//$result = array_unique($data);
  	asort($data);
  	//print_r($data);
  	$option = null;
	foreach($data as $date){
		if($option == null){
			$option ='<option value="' . $date['date'] . '">' . $date['date'] . '</option>';
		}else{
			$option = $option . '<option value="' . $date['date'] . '">' . $date['date'] . '</option>';
		}
	}
	//echo '<select name="date" id="dateform" onchange="">' . $option . '</select>';
	echo $option;
?>
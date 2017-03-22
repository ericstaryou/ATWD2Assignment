<?php 
	$station = htmlspecialchars($_GET["station"]);
  	$loc_name = str_replace(' ', '_', $station);
  	$file_name = strtolower($loc_name).'_no2.xml';
	
	$reader = new XMLReader();
  	$reader->open($file_name);

  	$data = array();

  	while($reader->read()){
  		if($reader->nodeType === XMLREADER::ELEMENT && $reader->localName === 'reading'){
  			$reading = array();
  			$date = $reader->getAttribute('date');
  			$dateForSort = str_replace('/', '-', $date);
        		$reading['sortdate'] = date('Y-m-d', strtotime($dateForSort));
  			$reading['date'] = $date;
  			$data[] = $reading;
  		}
  	}

  	//remove duplicate values from a multi-dimensional array 
  	//ref: http://stackoverflow.com/questions/307674/how-to-remove-duplicate-values-from-a-multi-dimensional-array-in-php
  	$data = array_map("unserialize", array_unique(array_map("serialize", $data)));
	
	//sort the data according to the date
  	asort($data);

	//generate date dropdown options 
  	$option = null;
	foreach($data as $date){
		if($option == null){
			$option ='<option value="' . $date['date'] . '">' . $date['date'] . '</option>';
		}else{
			$option = $option . '<option value="' . $date['date'] . '">' . $date['date'] . '</option>';
		}
	}
	
	echo $option;
?>

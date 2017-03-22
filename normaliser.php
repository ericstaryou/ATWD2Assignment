<?php
$loc = array (
	3 => 'Brislington',
	6 => 'fishponds',
	8 => 'parson st',
	9 => 'rupert st',
	10 => 'wells road',
	11 => 'newfoundland way'
);

foreach($loc as $key => $val){
	$loc_name = str_replace(' ', '_', $val);
	$file_name = strtolower($loc_name).'.xml';
	
	$reader = new XMLReader();
	$reader->open($file_name);
	
	//Outer array than contains all the rows
	$records = array();
	$lat = null;
	$long = null;

	#Start reading attribute from XML and storing into array using XMLReader 
	while($reader->read()){
		if($reader->nodeType === XMLREADER::ELEMENT && $reader->localName === 'row'){
			#inner array that contains all the required attributes for a particular row
			$row = array();
			$row['count'] = $reader->getAttribute('count');
		}
		
		if($reader->nodeType === XMLREADER::ELEMENT && $reader->localName === 'date'){
			$row['date'] = $reader->getAttribute('val');
		}
		
		if($reader->nodeType === XMLREADER::ELEMENT && $reader->localName === 'time'){
			$row['time'] = $reader->getAttribute('val');
		}
		
		if($reader->nodeType === XMLREADER::ELEMENT && $reader->localName === 'no2'){
			$row['no2'] = $reader->getAttribute('val');
		}
		
		if(!isset($lat) && $reader->nodeType === XMLREADER::ELEMENT && $reader->localName === 'lat'){
			$row['lat'] = $reader->getAttribute('val');
		}
		
		if(!isset($long) && $reader->nodeType === XMLREADER::ELEMENT && $reader->localName === 'long'){
			$row['long'] = $reader->getAttribute('val');
			#adds 'filled' $row array to the main array which is $records array
			$records[] = $row;
		}
	}
	
	$reader->close();
	
	#Start writing XML file using XMLWriter
	$type = 'nitrogen dioxide';
	$desc = $val;
	$lat = $row['lat'];
	$long = $row['long'];

	$writer = new XMLWriter();  
	$writer->openURI(strtolower($loc_name) . '_no2.xml');  
	$writer->startDocument('1.0','UTF-8');  
	$writer->setIndent(4);   
	$writer->startElement('data');  
		$writer->writeAttribute('type', $type);    
		$writer->startElement('location');
			$writer->writeAttribute('id', $desc);
			$writer->writeAttribute('lat', $lat);
			$writer->writeAttribute('long', $long);
			
			#Data that was collected using XMLReader and was stored inside $records array is written as XML attribute values 
			foreach($records as $row){
				$writer->startElement('reading');
					$writer->writeAttribute('date', $row['date']);
					$writer->writeAttribute('time', $row['time']);
					$writer->writeAttribute('val', $row['no2']);
				$writer->endElement();
			}
			
		$writer->endElement();    
	$writer->endElement();  
	$writer->endDocument();   
	$writer->flush();	
}
	
echo "done!"

?>

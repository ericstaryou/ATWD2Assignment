# ATWD2Assignment
## Streaming Parsers vs DOM Parsers
According to Kak (2012), parsers for XML document are categorised under event-driven parsers and tree-building parsers. An event-driven parser, such as SAX parsers works by reading a XML document character by character from the start of the document to the end of it. While doing so, it triggers events based on the character it is seeing at the current instance and the preceding characters. Kak (2012) also states that “A commercial-grade XML parser will typically conform to either the SAX API or W3C DOM API”, where the SAX API is a standardized API for event-driven parsing of XML documents. Moreover, Simple Programming Interface for XML (SAX) is a low-level and event based model of XML parsing (Krishnamurthi and Ramakrishnan, 2003). 
  
As for tree-building parsers, it works by scanning the whole document and construct the parse tree before it is being used as the document object model (DOM) for the document. Moreover, the DOM is considered a pull model where information from a document is being extracted by a client program via methods call (Harold, 2002). DOM are used mostly for document alteration and content search where traversing the trees into arbitrary nodes are required. Thus, for DOM parsers to work, it must load the whole document into the memory before the start of any operations.  

In the assignment, streaming parser (`XMLReader()`) is used to further normalise 6 other fairly large XML data files into the following structure instead of a DOM parser.

```XML
<?xml version="1.0" encoding="UTF-8"?>
<data type="nitrogen dioxide">
    <location id="wells road" lat="51.427" long="-2.568">
        <reading date="13/02/2016" time="03:15:00" val="11"/>
        <reading date="13/02/2016" time="03:30:00" val="11"/>
        <reading date="13/02/2016" time="03:45:00" val="11"/>

        <!-- thousands of other rows -->

        <reading date="13/02/2017" time="16:15:00" val="35"/>
    </location>
</data>
```
The advantages of streaming parsers are that they are more efficient as compared to DOM. It uses almost no memory and it is much faster based on the way it works (Harold, 2002). However, a streaming parser is not able to access multiple documents at the same time since it cannot navigate an XML document. Thus, the primary reason for using a streaming parser instead of a DOM parser such as `SimpleXML()` is that those files to be normalised are too large where it is impossible to load the whole document into the DOM tree before the normalisation process. Secondly, only a small contiguous chunk of input is needed to do the process instead of the whole document (Harold, 2002). In other word, the processes needed to normalise the XML data files do not depend on what comes later in the document. Lastly, no modifications are needed to be performed on the XML data files in the assignment, reading the XML data files is enough to serve the purpose of the normalisation. The code snippet below extracted from [normaliser.php](https://github.com/ericstaryou/ATWD2Assignment/blob/master/normaliser.php) shows that the `XMLReader()`object is used to move the cursor from the start of the document to the end of it via a while loop. It also shows that the `XMLReader()` object is used to read the value of each matching attribute where the cursor is pointing at the moment.

```php
$reader = new XMLReader();
$reader->open($file_name);

#Outer array than contains all the rows
$records = array();
$lat = null;
$long = null;

##Start reading attribute from XML and storing into array using XMLReader 
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
```
### Summary 
| Aspects | Stream Parser | DOM Parser | 
| ------ | ----------- | -----------|
| **Memory Usage**   | Does not store anything to memory |  Loads the whole document into memory |
| **Speed** | High | Low |
| **Usability**    | Difficult to use | Easy to use |
| **Access To Several Part of the Document at the Same Time**    | No | Yes |
| **Alter XML Document Repeatedly**    | No | Yes |

## Extension of Charting and Data Visualization
Google Scatter chart and a Google Line chart are used to represent the data queried from the normalised XML data files. The scatter chart can show a year (2016) worth of nitrogen dioxide (NO2) concentration data from a specific station at a specific hour (0800) of the day. User can select a specific station from the dropdown and the scatter chart will render the NO2 concentration data of every day that has the 0800 hours record for that specific station. As for the line chart, it shows the NO2 value in any 24-hour period on any day for any of the six stations. User can select the station and available date. 

For the charts to offer more information, some visibility extension can be done. One of the implemented extensions is by colour encoding different levels of pollution based on the concentration of NO2 according to the [DEFRA Site](https://uk-air.defra.gov.uk/air-pollution/daqi).

![Index Band](https://github.com/ericstaryou/ATWD2Assignment/blob/master/NO2%20band.PNG)

Data points on the charts are coloured differently based on which band they fall in. The code snippet extracted from [scatterchartGen.php](https://github.com/ericstaryou/ATWD2Assignment/blob/master/scatterchartGen.php) shows that different data points are assigned with different colour code. 

```php
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
```
The figure below shows the resulting scatter chart with colour encoded data points.

![scatterchart](https://github.com/ericstaryou/ATWD2Assignment/blob/master/scatterchart%20Example.PNG) 

Moreover, as mentioned earlier, the line chart only shows the value for only one station at a time. It can be improved by showing the values of all six stations of the selected date if the XML data files holds the record. Thus, there will be at most six lines on the line chart on one instance. This could help the user to compare the level of pollution based on the NO2 concentration between stations on the same day. 

Other than just showing the value of NO2, a bar chart can be used to show the value of other pollutants the original CSV data file holds such as NO and NOx. The bar chart should be able to show the concentration level of NO2, NO, and NOx of all six stations on a specific date and time. It could even show the average pollution level of each type of pollutants for a whole year. A bar chart example is shown in the figure below. 

![Bar Chart](https://github.com/ericstaryou/ATWD2Assignment/blob/master/Bar%20chart.PNG)

## References
Harold, E.R. (2002) *Choosing between SAX and DOM*. Available from: http://www.cafeconleche.org/books/xmljava/chapters/ch09s11.html [Accessed 19/03/2017]. 

Kak, A.C. (2012) *Scripting with Objects: A Comparative Presentation of Object-Oriented Scripting with Perl and Python* [online]. John Wiley & Sons. [Accessed 3/17/2017 2:58:17 PM].

Krishnamurthi, S. and Ramakrishnan, C. (2003) *Practical Aspects of Declarative Languages: 4th International Symposium, PADL 2002, Portland, OR, USA, January 19-20, 2002. Proceedings* [online]. Springer. [Accessed 3/17/2017 2:55:40 PM]. 

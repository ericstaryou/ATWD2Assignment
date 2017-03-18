# ATWD2Assignment
## Streaming Parsers vs DOM Parsers
According to Kak (2012), parsers for XML document are categorised under event-driven parsers and tree-building parsers. An event-driven parser, such as SAX parsers works by reading a XML document character by character from the start of the document to the end of it. While doing so, it triggers events based on the character it is seeing at the current instance and the preceding characters. Kak (2012) also states that “A commercial-grade XML parser will typically conform to either the SAX API or W3C DOM API”, where the SAX API is a standardized API for event-driven parsing of XML documents. Moreover, Simple Programming Interface for XML (SAX) is a low-level and event based model of XML parsing (Krishnamurthi and Ramakrishnan, 2003). 
  
As for tree-building parsers, it works by scanning the whole document and construct the parse tree before it is being used as the document object model (DOM) for the document. Moreover, the DOM is considered a pull model where information from a document is being extracted by a client program via methods call (Harold, 2002). DOM are used mostly for document alteration and content search where traversing the trees into arbitrary nodes are required. Thus, for DOM parsers to work, it must load the whole document into the memory before the start of any operations. 

In the assignment, `XMLReader()`, a streaming parser is used to further normalise 6 other fairly large XML data files into this following structure. 
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

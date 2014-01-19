<?php

class xml2array 
{
   var $arrOutput = array();
   var $arrName = array();
   var $objParser;
   var $strXmlData;
 
   function parse()
   {
      $handeler = fopen("C:/xampp/htdocs/dp/ext/xmlparser/meeting_minutes.xml", "r");
      $index = 0;

      while ( !feof($handeler))
      {
         $data = fgets($handeler);
         
         if ($name  = $this->getMatch('|<meeting_name>(.*)</meeting_name>|Uis', $data))
            $meeting[$index]['name']  = $name;
         if($date = $this->getMatch('|<meeting_date>(.*)</meeting_date>|Uis', $data))   
            $meeting[$index]['date']  = $date;
         if ($venue = $this->getMatch('|<meeting_venue>(.*)</meeting_venue>|Uis', $data))
            $meeting[$index]['venue'] = $venue;
         if ($time = $this->getMatch('|<meeting_time>(.*)</meeting_time>|Uis', $data))
         $meeting[$index]['time']  = $time;
         
         // checking whether it is the last tag, then increase the index      
         if ($time)
           $index++;
      }
      echo "<pre>";
      print_r($meeting);
   
   }
   
   function getMatch($regex, $content, $index=1) 
   {
      preg_match($regex, $content, $matches);
      
      return $matches[(int)$index];
   }


/*   function parse($strInputXML) 
   {
      // standard XML parse object setup      
      $this->objParser = xml_parser_create ();
      
      xml_set_object($this->objParser,$this);
      xml_set_element_handler($this->objParser, "tagOpen", "tagClosed");
      
      xml_set_character_data_handler($this->objParser, "tagData");
      
      $this->strXmlData = xml_parse($this->objParser,$strInputXML );
      
      if(!$this->strXmlData) 
      {
         die(sprintf("XML error: %s at line %d",
                                                xml_error_string(xml_get_error_code($this->objParser)),
                                                xml_get_current_line_number($this->objParser)));
      }
         
      xml_parser_free($this->objParser);
      
      return $this->arrOutput;
   }

  function tagOpen($parser, $name, $attrs) 
  {
     // push the current tag name to an array of still-open tag names
     array_push ($this->arrName, $name);
     echo("Name ::: $name<br>");
     
     // merge the array of current attributes to the open tag
     // NOTE: this does not currently handle multiple attributes with the same name
     // (i.e. it will overwrite them with the last values)
     
     $strEval = "\$this->arrOutput";

     foreach ($this->arrName as $value) 
     {
        $strEval .= "['" . $value . "']";
     }
     echo "STR EVAL ::: $strEval<br>";
     $strEval .= " = array_merge (" . $strEval . ",\$attrs);";
     echo($strEval);
     eval ($strEval);
  }

  function tagData($parser, $tagData) 
  {  
     // set the latest open tag equal to the tag data
     $strEval = "\$this->arrOutput";

     foreach ($this->arrName as $value) {
       $strEval .= "[" . $value . "]";
     }
     
     $strEval = $strEval . " = \$tagData;";
     
     eval ($strEval);
  }

 
  function tagClosed($parser, $name) 
  {
     // pop this tag (and any subsequent tags) off the stack of open tag names
     for ($i = count ($this->arrName) - 1; $i > 0; $i--) 
     {
        $currName = $this->arrName[$i];
        array_pop ($this->arrName);
        
        if ($currName == $name) 
        {
           break;
        }
     }
  }*/

}

?>
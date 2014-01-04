
function moveOnBtnClick(from, to)
{
   frm = document.frm_first_rpt_injury;
   
   var i;
   
   for(i=0; i<from.length;)
   {
      if(from[i].selected)
      {
         InsertIntoList(to, from.options[i].text, from.options[i].value, -1) ;
         //var newOpt = new Option(from.options[i].text, from.options[i].value);
         //to.options[to.length] = newOpt;
         from.options[i] = null;
         continue;
      }
      i++;
   }
   
   //sortSelBox(from);
   //sortSelBox(to);
   
}

function InsertIntoList(Combo, itemText, itemVal, Pos)
{
   var inserted   = false;
   var insertpos  = -1;
   
   // add blank row
   Combo.length++;
   
   if (Pos == -1)
   {
      // find alpha pos for insert
      for (var x=0; x<Combo.length - 1; x++)
      {	
         if (Combo.options[x].text != 'Choose One' && Combo.options[x].text.toLowerCase()>itemText.toLowerCase())
      	 {
      	    insertpos = x;
      	    break;
      	 }
      }
   }
   else
   {
      insertpos = Pos;
   }
   
   // shift part of array down one then insert new row	
   if(insertpos != -1)
   {	
      for (var y=Combo.length-1; y>insertpos; y--)
   	  {
   		   Combo.options[y].value  = Combo.options[y-1].value;
   		   Combo.options[y].text   = Combo.options[y-1].text;
   	  }
   	
   	Combo.options[insertpos].text   = itemText;
   	Combo.options[insertpos].value  = itemVal;
   }
   else
   // append new row
   {
      insertpos = Combo.length-1;
      Combo.options[insertpos].text   = itemText;
      Combo.options[insertpos].value  = itemVal;
   }
   
   return insertpos;
}

function sortSelBox(selBox)
{
   for(i = 0; i < selBox.length; i++) 
   {
      for(j = i+1; j < selBox.length; j++) 
      {
         if(selBox.options[i].text.toLowerCase()>selBox.options[j].text.toLowerCase())
         //if(selBox.options[i].value>selBox.options[j].value)
         {
           var temp1 = selBox.options[i].value;
           var temp2 = selBox.options[i].text;
           selBox.options[i].value = selBox.options[j].value;
           selBox.options[i].text  = selBox.options[j].text;
      
           selBox.options[j].value = temp1;
           selBox.options[j].text  = temp2;
         }
      }
   }   
}

function encodeHTML(encodedHtml)
{
   encodedHtml = encodedHtml.replace(/&/g,"&amp;");
   encodedHtml = encodedHtml.replace(/"/g,"&quot;");
   encodedHtml = encodedHtml.replace(/'/g,"&#039;");
   encodedHtml = encodedHtml.replace(/</g,"&lt;");
   encodedHtml = encodedHtml.replace(/>/g,"&gt;");
   
   return encodedHtml;
}

function selectDropDownItems(obj, val)
{
   //alert(obj.name);
   //alert(val);
   for(i=0; i<obj.length; i++)
   {
      if(val == obj.options[i].value)	
      {
         obj.options[i].selected = true;
         break;	
      }
   }
   
   if(i == obj.length)
   {
      obj.options[0].selected = true;	
   }
}

function selectRadioItem(obj, val)
{
   for(i=0; i<obj.length; i++)
   {
      if(val == obj[i].value)
      {
         obj[i].checked = true;	
         break;
      }	
   }
}

function mask(str,textbox,loc,delim, ev)
{                                                                                                            
   var locs = loc.split(',');
   var delims = delim.split(',');

   //alert(event.keyCode);
   for (var i = 0; i <= locs.length; i++)
   {
      for (var k = 0; k <= str.length; k++)
      {
         if (k == locs[i])
         {
            if (str.substring(k, k+1) != delims[i])
            {
               if (ev.keyCode != 8)
               {
                  str = str.substring(0,k) + delims[i] + str.substring(k,str.length);
               }
            }
         }
      }
   }
   
   textbox.value = str.toUpperCase();
}


function loadAppointment(appt, script_url, appt_id, treatment_id)
{
   var apptDropdown = document.getElementById('appt_test');
   
   document.getElementById('appointment_field').innerHTML = apptDropdown[apptDropdown.selectedIndex].text;
   
   var iframe     = document.getElementById('frame_search');
   var url_string = script_url+'?cmd=show_report&appt_id='+appt.value+'&treatment_id='+treatment_id;

   iframe.src = url_string;

}

function showLayer(str, el_id)
{
   var obj = parent.document.getElementById(el_id);
   obj.style.position = "absolute";
   obj.style.display  = "inline";
   obj.innerHTML      = str;
   //parent.document.getElementById('loading_td').bgColor = "#FF0000";
}

function hideLayer(el_id)
{
   var obj = parent.document.getElementById(el_id);
   
   obj.innerHTML = "";
}



/*/
//This function is responsible for forcing user to leave a valid DOB input
//The DOB format is mm/dd/yyyy or yyyy/mm/dd or mm-dd-yyyy or yyyy-mm-dd
// @dtTxt      :   Current Date text
// @format     :   Format of Date
// @delim      :   the character that is used in the format
// @repDelim   :   the character that is to be changed for delim param
*/ 
function setDateFormat(dtTxt,format,delim,replDelim)
{
  //tacking the value
  dtTxt            =  dtTxt.value;
  delimAscii       = delim.charCodeAt(0);
  replDelimAscii   = replDelim.charCodeAt(0);

	if(format == 'mm/dd/yyyy' || format == 'mm-dd-yyyy')
	{
    //checking for the positions of 2 and 5 i.e mm/dd/ here first '/' is in position of 2 and 3 for 
	  //the second one
	  if(dtTxt.length == 2 || dtTxt.length == 5)
	  {	
  	  //If the ascii value of pressed key is delim then it meant that a forward slash is just pressed
  	  //In this case we need this to be changed as ascii 47 as a forward slash
			if(window.event.keyCode == replDelimAscii)
			{
          window.event.keyCode = delimAscii;
			}
			//If the key pressed is not a forward slash then we have to add a forward slash
			if(window.event.keyCode != delimAscii )
			{
				 document.getElementById('dob').innerText  = dtTxt + delim; 
			}
	  }
	} 
	  
	else if(format == 'yyyy/mm/dd' || format == 'yyyy-mm-dd')
	{
	  //checking for the positions of 4 and 7 i.e yyyy/mm/ here first '/' is in position of 4 and 7 for 
	  //the second one
	  if(dtTxt.length == 4 || dtTxt.length == 7)
	  {	
  	   //If the ascii value of pressed key is 45 then it meant that a forward slash is just pressed
  	   //In this case we need this to be changed as ascii 47 as a forward slash
		  if(window.event.keyCode == delimAscii)
		  {
           window.event.keyCode = replDelimAscii;
		  }
		  //If the key pressed is not a forward slash then we have to add a forward slash
		  if(window.event.keyCode != delimAscii )
		  {
		  	 document.getElementById('dob').innerText  = dtTxt + delim; 
		  }
	  }
	}
}
   
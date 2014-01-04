/*
 *   File: report_manager.js
 *
 */

var itemStatusArray  = new Array('B', 'C', 'P');
var months           = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];

function validateFields(frm)
{
   with(frm)
   {
   }

    return true;
}

function doFormSubmit()
{
   requiredFields.length = 0;

   var errCnt = 0;
   var frm = document.memoForm;
   
   // Setup required fields
   setupForm(frm);

   // Validate form for required fields
   errCnt = validateForm(frm);

   if (errCnt)
   {
      alert(MISSING_REQUIRED_FIELDS);
      return false;
   }

   else
   {
      if ( $('#dup_br_code').val() == 1)
      {
          alert('Branch Code is not valid');
          highlightTableColumn('code');
          return false;
      }
      
      if(validateFields(frm))
      {
         return true;
      }
      else
         return false;
   }
}

/**
 * Generate the status dropdown list 
 * @returns {String}
 */
function getStatusList()
{
    var selectObj = '<select name="status" id="status" class="select W50">';
    
    for (var i = 0; i < itemStatusArray.length; i++)
    {
        selectObj += '<option value="'+itemStatusArray[i]+'">'+itemStatusArray[i]+'</option>';
    }
    
    document.write(selectObj);
}

/**
 * pad a number with leading 0
 * @param {int} number
 * @param {int} pad
 * @returns {Array}
 */

function padNumber(number, pad) 
{
    return Array(Math.max(pad - String(number).length + 1, 0)).join(0) + number;
}

/**
 * Generate the month list for start month and end month dropdown
 * @param {type} elemName
 * @returns {String}
 */

function getMonthList(elemName)
{
    var dt        = new Date();
    var year      = dt.getFullYear();
    
    var selectObj = '<select name="'+elemName+'" id="'+elemName+'" class="select W75">';
    
    if (elemName == 'end_month')
    {
        selectObj = selectObj + '<option value="Ongoing">Ongoing</option>';
    }
    
    for (var i = dt.getMonth(); i <= 24; i++)
    {
        if ( i%12 == 0 )
        {
            year++;
        }
        yr2 = year.toString().substring(2);
        
        selectObj = selectObj + '<option value="'+yr2+padNumber(i%12+1, 2)+'">'+months[i%12]+' '+yr2+'</option>';
    }
    
    selectObj = selectObj + '</select>';
    
    document.write(selectObj) ;
}
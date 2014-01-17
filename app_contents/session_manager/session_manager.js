/*
 *   File: session_manager.js
 *
 */

RE_NAME     = new RegExp(/[^A-Z^a-z^ ^\.\^]$/);
RE_EMAIL    = new RegExp(/^[A-Za-z0-9](([_|\.|\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([_|\.|\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]{2,})$/);
RE_USERNAME = new RegExp(/^[a-z0-9\_]+$/);
RE_DECIMAL  = new RegExp(/^[0-9]{1,8}([\.]{1}[0-9]{1,2})?$/);
RE_NUMBER   = new RegExp(/^[0-9]+$/);
RE_PHONE    = new RegExp(/^((\d\d\d)|(\(\d\d\d\)))?\s*[\.-]?\s*(\d\d\d)\s*[\.-]?\s*(\d\d\d\d)$/);
RE_ZIP      = new RegExp(/^[0-9]{5}(([\-\ ])?[0-9]{4})?$/);

var ACTIVE_SESSION_EXISTS_MSG = 'Sorry!!! you can not open this session. One session is still Active.';

var active_session = false;

function setupForm(frm)
{
   with (frm)
   {
      setRequiredField(session_year,            'textbox',   'session_year');
      setRequiredField(session_status,          'dropdown',  'session_status');
      setRequiredField(application_start_date,  'textbox',   'application_start_date');
      setRequiredField(application_end_date,    'textbox',   'application_end_date');
      setRequiredField(scholarship_bulk_amount, 'textbox',   'scholarship_bulk_amount');
   }
}

function validateFields(frm)
{
    with(frm)
    {
        if (!RE_NUMBER.exec(delivery_date.value))
        {
            highlightTableColumn('delivery_date');
            alert('Please enter a number');
            return false;
        }

        return true;
    }
}

function doFormSubmit()
{
    requiredFields.length = 0;

    var errCnt = 0;
    var frm = document.sessionForm;

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
        if (active_session)
        {
            highlightTableColumn('session_status');
            alert(ACTIVE_SESSION_EXISTS_MSG);
            active_session = true;                        
            return false;
        }
        
        return true;
    }
}

function doClearForm(frm)
{
    $("#sessionForm")[0].reset();
}

function checkSessionStatus()
{
    var session_status = $('#session_status').val();
    
    if (session_status === 'Active')
    {
        $.ajax
        (
            {                                      
                url: 'session_manager.php?cmd=checksession',                    //the script to call to get data          
                data: "session_status="+session_status,                               //you can insert url argumnets here to pass to api.php   //for example "id=5&parent=6"
                dataType: 'json',                                         //data format      
                success: function(responseText)                           //on recieve of reply
                {
                    if ( responseText != '')
                    {
                        highlightTableColumn('session_status');
                        alert(ACTIVE_SESSION_EXISTS_MSG);
                        active_session = true;
                        return false;
                    }
                    else
                    {
                        resetTableColumn('session_status');
                        active_session = false;
                    }
                }
            } 
        );  
    }
    else
    {
        resetTableColumn('session_status');
        active_session = false;
    }
}
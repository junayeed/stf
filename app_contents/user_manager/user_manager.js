/*
 *   File: user_manager.js
 *
 */

RE_NAME          = new RegExp(/[^A-Z^a-z^ ^\.\^]$/);
RE_EMAIL         = new RegExp(/^[A-Za-z0-9](([_|\.|\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([_|\.|\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]{2,})$/);
RE_USERNAME      = new RegExp(/^[A-Za-z0-9\_]+$/);
RE_DECIMAL       = new RegExp(/^[0-9]{1,8}([\.]{1}[0-9]{1,2})?$/);
RE_NUMBER        = new RegExp(/^[0-9]+$/);
RE_PHONE         = new RegExp(/^((\d\d\d)|(\(\d\d\d\)))?\s*[\.-]?\s*(\d\d\d)\s*[\.-]?\s*(\d\d\d\d)$/);
RE_ZIP           = new RegExp(/^[0-9]{5}(([\-\ ])?[0-9]{4})?$/);
RE_UK_POSTCODE   = new RegExp(/^([A-PR-UWYZ0-9][A-HK-Y0-9][AEHMNPRTVXY0-9]?[ABEHMNPRVWXY0-9]? {0,2}[0-9][ABD-HJLN-UW-Z]{2}|GIR ?0AA)$/);

var duplicate_user   = false;
var duplicate_email  = false;

function doUserSearch(user_type, user_status, elemType, elemID)
{
    var userTypeArray     = ['_all', '_admin', '_employee'];
    var userStatusArray   = ['_all', '_active', '_inactive'];
    
    if (elemType == 'user_type')
    {
        for(var i=0; i<userTypeArray.length; i++)
        {
            if($('#'+elemType+userTypeArray[i]).hasClass('bold_text'))
            {
                $('#'+elemType+userTypeArray[i]).removeClass('bold_text');
            }
        }
    }
    
    $('#'+elemType+elemID).addClass('bold_text');
    
    if (elemType == 'user_status')
    {
        for(var j=0; j<userStatusArray.length; j++)
        {
            if($('#'+elemType+userStatusArray[j]).hasClass('bold_text'))
            {
                $('#'+elemType+userStatusArray[j]).removeClass('bold_text');
            }
        }
    }
    
    $('#'+elemType+elemID).addClass('bold_text');
    
    var source         = document.getElementById('usersFrame').src;
    
    $('#usersFrame').attr('src', source+'&user_type=' + user_type + '&user_status=' + user_status);
}

function setupForm(frm)
{ 
    with (frm)
    {
        setRequiredField(username,           'textbox',   'username');
        setRequiredField(user_status,        'dropdown',  'user_status');
        setRequiredField(user_type,          'dropdown',  'user_type');
        setRequiredField(first_name,         'textbox',   'first_name');
        setRequiredField(last_name,          'textbox',   'last_name');
        //setRequiredField(dob,                'textbox',   'dob');
        //setRequiredField(gender,             'dropdown',  'gender');
        //setRequiredField(maritial_status,    'dropdown',  'maritial_status');
        //setRequiredField(postcode,           'textbox',   'postcode');
        setRequiredField(email,              'textbox',   'email');
        //setRequiredField(home_phone,         'textbox',   'home_phone');
        //setRequiredField(mobile,             'textbox',   'mobile');
        //setRequiredField(permanent_address,  'textbox',   'permanent_address');
        //setRequiredField(city,               'dropdown',  'city');

        // if the password field is set i.e. we have the value for password mainly in the edit mode.
        if ( !$('#psw').val())
        {
            setRequiredField(password,           'textbox',   'password');
        }
    }
}

function validateFields(frm)
{
    with(frm)
    {
        if (!RE_USERNAME.exec(username.value))
        {
            highlightTableColumn('username');
            alert(ERROR_USERNAME);
            return false;
        }
        else if (RE_NAME.exec(first_name.value))
        {
            highlightTableColumn('first_name');
            alert(ERROR_NAME);
            return false;
        }
        else if (RE_NAME.exec(last_name.value))
        {
            highlightTableColumn('last_name');
            alert(ERROR_NAME);
            return false;
        }
//        else if ( !RE_UK_POSTCODE.exec(postcode.value) )
//        {
//            highlightTableColumn('postcode');
//            alert(ERROR_UK_ZIPCODE);
//            return false;
//        }

        return true;
    }
}

function doFormSubmit()
{
    requiredFields.length = 0;

    var errCnt = 0;
    var frm = document.userManagerForm;

    // Setup required fields
    setupForm(frm);
    
    // Validate form for required fields
    errCnt = validateForm(frm);

    if (errCnt)
    {
        alert(MISSING_REQUIRED_FIELDS);
        return false;
    }
    else if (duplicate_user)  // for duplicate username
    {
        highlightTableColumn('username');
        alert(DUPLICATE_USERNAME);
        return false;
    }
    else if (duplicate_email)  // for duplicate email ID
    {
         highlightTableColumn('email');
         alert(DUPLICATE_EMAIL);
         return false;
    }
    else
    {
        if(validateFields(frm))
        {
            return true;
        }
        else
            return false;
    }
    
    return true;
}

function doUpperCase(obj) 
{
    obj.value=obj.value.toUpperCase();
}

function checkUserName()
{
    var username = $('#username').val();

    $.ajax
    (
        {                                      
            url: 'user_manager.php?cmd=checkuser',                    //the script to call to get data          
            data: "username="+username,                               //you can insert url argumnets here to pass to api.php   //for example "id=5&parent=6"
            dataType: 'json',                                         //data format      
            success: function(responseText)                           //on recieve of reply
            {
                if ( responseText != '')
                {
                    highlightTableColumn('username');
                    alert(DUPLICATE_USERNAME);
                    duplicate_user = true;
                    return false;
                }
                else
                {
                    resetTableColumn('username');
                    duplicate_user = false;
                }
            }
        } 
    );  
}

function checkUserEmail()
{
    var email = $('#email').val();

    $.ajax
    (
        {                                      
            url: 'user_manager.php?cmd=checkemail',                    //the script to call to get data          
            data: "email="+email,                               //you can insert url argumnets here to pass to api.php   //for example "id=5&parent=6"
            dataType: 'json',                                         //data format      
            success: function(responseText)                           //on recieve of reply
            {
                if ( responseText != '')
                {
                    highlightTableColumn('email');
                    alert(DUPLICATE_EMAIL);
                    duplicate_email = true;
                    return false;
                }
                else
                {
                    resetTableColumn('email');
                    duplicate_email = false;
                }
            }
        } 
    );  
}

function doClearForm()
{
    location.href = 'http://'+document.domain+'/app/user_manager/user_manager.php';
}
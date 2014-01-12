//
//  Purpose: set up the required fields for the current form
//           
//

RE_NAME          = new RegExp(/[^A-Z^a-z^ ^\.\^]$/);
RE_EMAIL         = new RegExp(/^[A-Za-z0-9](([_|\.|\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([_|\.|\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]{2,})$/);
RE_USERNAME      = new RegExp(/^[A-Za-z0-9\_]+$/);

var duplicate_user   = false;
var duplicate_email  = false;
var psw_match        = true;

function setupRegistrationForm()
{
   var frm = document.registrationForm;
   
   with (frm)
   {                   
      setRequiredField(first_name,       'textbox', 'first_name');
      setRequiredField(last_name,        'textbox',  'last_name');
      setRequiredField(email,            'textbox',  'email');
      setRequiredField(username,         'textbox',  'username');
      setRequiredField(gender,           'radio',    'gender');
      setRequiredField(password,         'textbox',  'password');
      setRequiredField(confirm_password, 'textbox',  'confirm_password');
   }
}

function doRegistrationFormSubmit()
{
    
    var errCnt = 0;
    var frm    = document.registrationForm;
   
    // Setup required fields
    setupRegistrationForm();
    
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
        else if (!RE_EMAIL.exec(email.value))
        {
            highlightTableColumn('email');
            alert(ERROR_EMAIL);
            return false;
        }

        return true;
    }
}

function isPSWMatched()
{
    if ($('#confirm_password').val())
    {
        if ($('#password').val() !== $('#confirm_password').val())
        {
            alert(ERROR_PASS_MATCH);
            psw_match = false;
            highlightTableColumn('password');
            highlightTableColumn('confirm_password');
        }
        else
        {
            psw_match = true;
            resetTableColumn('password');
            resetTableColumn('confirm_password');
        }
    }
}

function isUserNameExists()
{
    var username = $('#username').val();
    //alert(window.location.host)
    $.ajax
    (
        {                                      
            url:'http://'+window.location.host+ '/app/standard/registration/registration.php?cmd=checkuser',
            data: "username="+username,
            dataType: 'json',
            success: function(responseText)
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

function isEmailExists()
{
    var email = $('#email').val();

    $.ajax
    (
        {                                      
            url: 'http://'+window.location.host+ '/app/standard/registration/registration.php?cmd=checkemail',
            data: "email=" + email,
            dataType: 'json',
            success: function(responseText)
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
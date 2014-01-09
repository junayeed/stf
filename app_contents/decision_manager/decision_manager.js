/*
 *   File: application_manager.js
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

var row_id           = 1;

function setupForm(frm)
{ 
    with (frm)
    {
        //setRequiredField(username,           'textbox',   'username');
        //setRequiredField(user_status,        'dropdown',  'user_status');
        //setRequiredField(user_type,          'dropdown',  'user_type');
        setRequiredField(first_name,         'textbox',   'first_name');
        setRequiredField(last_name,          'textbox',   'last_name');
        setRequiredField(email,              'textbox',   'email');

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
        if (RE_NAME.exec(first_name.value))
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

function doApplicationSubmit()
{
    var ok;
    var frm = document.userManagerForm;
    
    if ( doConfirm("Are you sure to submit this applicaiton?" ) )
    {
        //user is sure to submit the application
        ok = doFormSubmit();
        //alert(ok);
        if (ok)
        {
            frm.submitted.value = 1;
            
            frm.submit();
        }
    }
    else
    {
        
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

function isUserEmailExists()
{
    var email = $('#email').val();

    $.ajax
    (
        {                                      
            url: 'application_manager.php?cmd=checkemail',                    //the script to call to get data          
            data: "email=" + email,                               //you can insert url argumnets here to pass to api.php   //for example "id=5&parent=6"
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

function populateAcademicDetails(id, uid, degree, attachmentname, file_location, doc_id)
{
    var elemID = row_id-1;
    
    $('#degree_'+elemID).val(degree);
    $('#aqid_'+elemID).val(id);
    $('#attachmentname_'+elemID).val(attachmentname);
    //$('#id_'+elemID).attr('href',file_location );
    $('#file_location_'+elemID).val(file_location);
    $('#id_'+elemID).show();
}


function openFancyBox(elemID)
{
    var file_loc = 'http://stf.local' + $('#file_location_'+elemID).val();
    
    $.fancybox.open
    (
        [
            {
                href : file_loc 
            }
        ], 
            {
                openEffect : 'elastic',
                openSpeed  : 150,
                closeEffect : 'elastic',
                closeSpeed  : 150
            },
            {
                helpers : 
                {
                    thumbs : 
                    {
                        width: 45,
                        height: 50
                    },
                    overlay : 
                    {
                        css : 
                        {
                            'background' : 'rgba(238,238,238,0.85)'
                        }
                    }
                }
            }
    );
}

function toggleOptions()
{
    var country = $('#country').val();
    
    if (country === 'US')
    {
        $('#others-qualifications').hide();
        $('#i20').show();
    }
    else
    {
        $('#i20').hide();
        $('#others-qualifications').show();
    }
}

function CheckAll(thisField)
{
    var i;
    //alert(AppIDs)
    
    if(thisField.checked==true)
    {
        for(i=0;i<AppIDs.length;i++)
        {
            document.getElementById('app_id_'+AppIDs[i]).checked = true;
        }
    }
    else
    {
        for(i=0;i<AppIDs.length;i++)
        {
            document.getElementById('app_id_'+AppIDs[i]).checked = false;
        }
    }    
}

function doApplicantSearch()
{
    var source                = document.getElementById('applicantFrame').src;
    var applicant_name        = $('#applicant_name').val();
    var email                 = $('#email').val();
    var country               = $('#country').val();
    var application_status    = $('#application_status').val();
    var gender                = $('#gender').val();
    var guardian_income_min   = $('#guardian_income_min').val();
    var guardian_income_max   = $('#guardian_income_max').val();
    var degree_list           = '';
    
    $("input[name='degree']").each( function () 
    {
       if ($(this).attr('checked'))
       {
           if (degree_list) degree_list += ', ';
           degree_list += '\'' + $(this).val() + '\'';
       }
    });
    
    $('#applicantFrame').attr('src', source+'&applicant_name='+applicant_name+'&email='+email+'&country='+country+
                                            '&application_status='+application_status+'&gender='+gender+'&guardian_income_max='+guardian_income_max+
                                            '&guardian_income_min='+guardian_income_min+'&degree='+degree_list);
}

function showApplicantInfo(elemID)
{
    //alert(elemID);
    //$('#std_details_'+elemID).fancybox();
    //$('#std_details_'+elemID).show();
    
    $.ajax
        (
            {
                url: 'applicant_manager.php?cmd=viewapp',                //the script to call to get data          
                data: "id="+elemID,
                dataType: 'html',                                        //data format      
                success: function(response)                          //on recieve of reply
                {
                        //alert(response);
                        $('#std_details_'+elemID).html(response);
                        $.fancybox.open({
                            'href'          : '#std_details_'+elemID,
                            'titleShow'     : false,
                            'transitionIn'  : 'elastic',
                            'transitionOut' : 'elastic'
                        });
                }    
            }
        );
    
   
}

function showSliders()
{
    $( "#location_slider" ).slider(
    {
        value:0,
        min: 1,
        max: 100,
        step: 1,
        slide: function( event, ui ) 
        {
            $( "#scholarship_percentage" ).val( ui.value );
        }
    }
    );
}

function distributeScholarshipAmount()
{
    for(i=0; i<AppIDs.length; i++)
    {
    
    }
}

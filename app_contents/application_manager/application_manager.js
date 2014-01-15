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
    var grant       = $('#received_grant').val();
    var country_val = $('#country').val();
    
    for(i=0; i<hasAttachmentsIDs.length; i++)
        {
            //if the doc id is found and file name is empty that means this field already has file
            //if the doc is in NOT found and file name is also EMPTY that means there is no attachments
            if (hasAttachmentsValues[i] == 0 && $('#'+hasAttachmentsIDs[i]).val() == '' )
            {
                alert('Id == ' +hasAttachmentsIDs[i] + '\n Values == ' + hasAttachmentsValues[i] + '\nFile Name == ' + $('#'+hasAttachmentsIDs[i]).val()+'\n Set Req');
                setRequiredField(hasAttachmentsIDs[i], 'file', hasAttachmentsIDs[i]);
            }
        }
        
       
    
    with (frm)
    {
        /*** Personal Information start here ***/
        //setRequiredField(photo,              'file',      'photo');
        setRequiredField(first_name,         'textbox',   'first_name');
        setRequiredField(last_name,          'textbox',   'last_name');
        setRequiredField(email,              'textbox',   'email');
        setRequiredField(permanent_address,  'textbox',   'permanent_address');
        setRequiredField(present_phone,      'textbox',   'present_phone');
        setRequiredField(present_address,    'textbox',   'present_address');
        setRequiredField(cell_phone,         'textbox',   'cell_phone');
        setRequiredField(gender,             'dropdown',  'gender');
        
        /*** Personal Information end here ***/
        
        /*** guardian Information start here ***/
        setRequiredField(guardian_name,         'textbox',   'guardian_name');
        setRequiredField(guardian_occupation,   'textbox',   'guardian_occupation');
        setRequiredField(guardian_income,       'textbox',   'guardian_income');
        //setRequiredField(guardian_income_tax,   'file',      'guardian_income_tax');
        /*** guardian Information end here ***/
        
        //var country = $('#country').val();
        //alert('Another Country ::: ' + country);
        /*** University Information start here ***/
        setRequiredField(country,                'dropdown',  'country');
        setRequiredField(university_name,        'textbox',   'university_name');
        setRequiredField(university_contact,     'textbox',   'university_contact');
        setRequiredField(subject_desc,           'textbox',   'subject_desc');
        //setRequiredField(acceptance_letter,      'file',      'acceptance_letter');
        //setRequiredField(scholarship_letter,     'file',      'scholarship_letter');
        //setRequiredField(enroll_certification,   'file',      'enroll_certification');
        //setRequiredField(enroll_certification,   'file',      'enroll_certification');
        
        if (country_val == 'US')
        {
            //setRequiredField(i20,   'file',      'i20');
        }
        /*** University Information end here ***/
        
        /*** Ticket Information start here ***/
        setRequiredField(ticket_number,     'textbox',   'ticket_number');
        setRequiredField(date_ticket,       'textbox',   'date_ticket');
        setRequiredField(ticket_fare,       'textbox',   'ticket_fare');
        setRequiredField(tax,               'textbox',   'tax');
        setRequiredField(ticket_fare_usd,   'textbox',   'ticket_fare_usd');
        setRequiredField(tax_usd,           'textbox',   'tax_usd');
        //setRequiredField(ticket_doc,        'file',      'ticket_doc');
        /*** Ticket Information start here ***/
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
        else if( !validateAcademicQualifications() )
        {
            alert('No academic qualification record found.\nPlease enter academic qualification information.');
            return false;
        }
        else if( !validateFileTypes() )
        {
            alert('Some of the file format is not supported.\nPlease enter a valid file.\nSuported file formats are: jpeg ,jpg, png, gif');
            return false;
        }

        return true;
    }
}

function doApplicationSubmit()
{
    var ok = true;
    var frm = document.userManagerForm;
    
    if ( doConfirm("Are you sure to submit this applicaiton?" ) )
    {
        //user is sure to submit the application
        //ok = doFormSubmit();
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

function validateFileTypes()
{
    var fileArray            = ['photo', 'guardian_income_tax', 'acceptance_letter', 'scholarship_letter', 'enroll_certification', 'i20', 'ticket_doc'];
    var validFileExtensions  = [".jpg", ".jpeg", ".gif", ".png"];
    var isValidFile;
    
    for (var i=0; i<fileArray.length; i++)
    {    
        var sFileName = $('#'+fileArray[i]).val();

        isValidFile = false;

        if(sFileName)
        {    
            for (var j = 0; j < validFileExtensions.length; j++) 
            {
                var sCurExtension = validFileExtensions[j];
        
                if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) 
                {
                    isValidFile = true;
                    break;
                }
            }
            if (isValidFile)
            {
                resetColumn(fileArray[i]);
            }
            else
            {
                highlightTableColumn(fileArray[i]);
                return false;
            }
        }
    }    
    return true;
}

function validateAcademicQualifications()
{
    if( $("#academic_qualifications > tbody").find("tr").length > 0 )
    {
        return true;
    }
    
    return false;
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

function addNewRow()
{
    var td_empty              = '<td width="4" class="prepand">&nbsp;</td>';
    var td_degree             = '<td width="47" class="prepand"> '+ getDegreeList() +'</td>';
    var td_attachment_name    = '<td width="63" class="prepand"><input type="text" name="attachmentname_'+row_id+'" id="attachmentname_'+row_id+'"    value="" class="inputbox3 W150" ></td>';  
    var td_attachment         = '<td width="70" class="prepand"><input type="file" name="academicfiles_'+row_id+'" onchange=uploadFiles("academicfiles_'+row_id+'")   id="academicfiles_'+row_id+'"    value="" class="W175" /></td>';
    var td_action             = '<td width="50" class="prepand" id="td_action_'+row_id+'"><a href="javascript: void(0);" onClick="deleteRow('+row_id+');"><img src="/app_contents/common/images/cross2.png"></a></td>';
    var td_view               = '<td width="50" class="prepand" id="td_view_'+row_id+'"><a id="id_'+row_id +'" href="#" onClick="openFancyBox('+row_id+');"><img src="/app_contents/common/images/view22.png"></a></td>';
    var hidden_field          = '<input type="hidden" id="aqid_'+row_id+'" name="aqid_'+row_id+'" value="" >\n\
                                 <input type="hidden" id="file_location_'+row_id+'" name="file_location_'+row_id+'" value="">';
     
    $('<tr id="tr_'+row_id+'" class="border">'+ td_empty+td_degree+td_attachment_name+td_view+td_attachment+td_action+hidden_field+'</tr>').prependTo("#academic_qualifications > tbody")
    $('#id_'+row_id).hide();
    row_id++;    
    
}

var degreeArray = ["", "S.S.C","O Levels", "Dakhil","H.S.C.", "A Levels", "Alim", "IB", "Bachelor", "Kamil","Masters", "Fazil","Ph.D"];

function getDegreeList()
{
    var selectObj = '<select name="degree_'+row_id+'" id="degree_'+row_id+'" class="combo1 W110">';
    
    for (var i = 0; i < degreeArray.length; i++)
    {
        selectObj += '<option value="'+degreeArray[i]+'">'+degreeArray[i]+'</option>';
    }
    
    return selectObj;    
}

function deleteRow(elemID)
{
   
    
    var id       = $('#aqid_'+elemID).val();
    
    if ( doConfirm(" Document will be deleted.\n" + PROMPT_DELETE_CONFIRM ) )
    {
        $.ajax
        (
            {
                url: 'application_manager.php?cmd=deletedoc',                //the script to call to get data          
                data: "id="+id,
                dataType: 'json',                                        //data format      
                complete: function(responseText)                          //on recieve of reply
                {
                        $("#academic_qualifications > tbody").find("#tr_" + elemID).fadeOut(1000,function() 
                        {
                            $('#academic_qualifications > tbody > #tr_' + elemID).remove();
                        });
                }    
            }
        );
    }
}

function copyAddress(srcID, destID)
{
    $('#'+destID).val( $('#'+srcID).val() );
}

function calculateFareBDT()
{
    var fare = $('#ticket_fare').val()*1;
    var tax  = $('#tax').val()*1;
    
    $('#total').val(fare+tax)
    
}


function calculateFareUSD()
{
    var fare = $('#ticket_fare_usd').val()*1;
    var tax  = $('#tax_usd').val()*1;
    
    $('#total_usd').val(fare+tax)
}

function openFancyBox(elemID)
{
    var file_loc = 'http://' +window.location.host + $('#file_location_'+elemID).val();
    
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
        $('#i-20').show();
    }
    else
    {
        $('#i-20').hide();
        $('#others-qualifications').show();
    }
}

function toggleGrantOptions()
{
    var grant = $('#received_grant').val();
    
    if (grant === 'Yes')
    {
        $('#td_received_grant_amount').show();
        $('#td_grant_received_date').show();
    }
    if (grant === 'No' || grant === '')
    {
        $('#td_received_grant_amount').hide();
        $('#td_grant_received_date').hide();
    }
}

function showAttachment(elemID)
{
    if ($('#'+elemID).val() > 0 && $('#'+elemID).val() != '')
    {
        $('#'+elemID+'_tr').show();
    }
    else
    {
        $('#'+elemID+'_tr').hide();
    }
}
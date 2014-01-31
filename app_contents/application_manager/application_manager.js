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

var degreeArray = ["", "S.S.C","O Levels", "Dakhil","H.S.C.", "A Levels", "Alim", "IB", "Bachelor", "Kamil","Masters", "Fazil","Ph.D"];

var duplicate_user   = false;
var duplicate_email  = false;
var OUTER_DELIM      = '###';
var row_id           = 1;
var noAcademicValue  = false;

function setupForm(frm, elemID)
{ 
    var grant       = $('#received_grant').val();
    var country_val = $('#country').val();
    
//    for(i=0; i<hasAttachmentsIDs.length; i++)
//    {
//        //if the doc id is found and file name is empty that means this field already has file
//        //if the doc is in NOT found and file name is also EMPTY that means there is no attachments
//        if (hasAttachmentsValues[i] == 0 && $('#'+hasAttachmentsIDs[i]).val() == '' )
//        {
//            //alert('Id == ' +hasAttachmentsIDs[i] + '\n Values == ' + hasAttachmentsValues[i] + '\nFile Name == ' + $('#'+hasAttachmentsIDs[i]).val()+'\n Set Req');
//            if (country_val === 'US' && hasAttachmentsValues[i] === 'i20')
//            {
//                setRequiredField(hasAttachmentsIDs[i], 'file', hasAttachmentsIDs[i]);
//                continue;
//            }
//            setRequiredField(hasAttachmentsIDs[i], 'file', hasAttachmentsIDs[i]);
//        }
//    }
        
       
    
    with (frm)
    {
        if (elemID === 'personal-info')
        {
            /*** Personal Information start here ***/
            setRequiredField(photo,                'file',      'photo');
            setRequiredField(first_name,           'textbox',   'first_name');
            setRequiredField(last_name,            'textbox',   'last_name');
            setRequiredField(email,                'textbox',   'email');
            setRequiredField(permanent_address,    'textbox',   'permanent_address');
            setRequiredField(present_phone,        'textbox',   'present_phone');
            setRequiredField(present_address,      'textbox',   'present_address');
            setRequiredField(cell_phone,           'textbox',   'cell_phone');
            setRequiredField(passport,             'textbox',   'passport');
            setRequiredField(passport_expire_date, 'textbox',   'passport_expire_date');
            setRequiredField(gender,               'dropdown',  'gender');
            setRequiredField(country_code,         'dropdown',  'country_code');

            /*** Personal Information end here ***/

            /*** guardian Information start here ***/
            setRequiredField(guardian_name,         'textbox',   'guardian_name');
            setRequiredField(guardian_occupation,   'textbox',   'guardian_occupation');
            setRequiredField(guardian_income,       'textbox',   'guardian_income');
            setRequiredField(guardian_income_tax,   'file',      'guardian_income_tax');
            //setRequiredField(tin,                   'textbox',   'tin');
            /*** guardian Information end here ***/
        }
        
        else if (elemID === 'university-info')
        {
            /*** University Information start here ***/
            setRequiredField(country,                'dropdown',  'country');
            setRequiredField(university_name,        'textbox',   'university_name');
            setRequiredField(university_contact,     'textbox',   'university_contact');
            setRequiredField(subject_desc,           'textbox',   'subject_desc');
            setRequiredField(acceptance_letter,      'file',      'acceptance_letter');
            setRequiredField(scholarship_letter,     'file',      'scholarship_letter');
            setRequiredField(enroll_certification,   'file',      'enroll_certification');
            setRequiredField(enroll_certification,   'file',      'enroll_certification');
        
            if (country_val == 'US')
            {
                setRequiredField(i20,   'file',      'i20');
            }
            /*** University Information end here ***/
        }
        else if (elemID === 'ticket-info')
        {
            /*** Ticket Information start here ***/
            setRequiredField(ticket_number,     'textbox',   'ticket_number');
            setRequiredField(date_ticket,       'textbox',   'date_ticket');
            setRequiredField(ticket_fare,       'textbox',   'ticket_fare');
            setRequiredField(tax,               'textbox',   'tax');
            setRequiredField(ticket_fare_usd,   'textbox',   'ticket_fare_usd');
            setRequiredField(tax_usd,           'textbox',   'tax_usd');
            setRequiredField(ticket_doc,        'file',      'ticket_doc');
            
            if( !$('#destination_airport_tb').is(':disabled'))
            {
                setRequiredField(destination_airport_tb,   'textbox',  'destination_airport_tb');
            }//
            else
            {
                setRequiredField(destination_airport_dd,   'dropdown',  'destination_airport_dd');
            }
            
            /*** Ticket Information end here ***/
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
    var frm = document.applicationPreviewForm;
    
    if ( doConfirm("Are you sure to send this applicaiton?" ) )
    {
        frm.submitted.value = 1;
        frm.cmd.value = 'submit_app';
        frm.submit();
    }
    else
    {
        
    }
}

function doApplicationPreview()
{
    var ok = true;
    var frm = document.userManagerForm;
    
    frm.cmd.value = 'preview-app';
    frm.preview.value = 1;
    frm.submit();
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
    var elemArray  = ['degree_', 'result_','attachmentname_c_', 'attachmentname_t_', 'academicfiles_c_', 'academicfiles_t_'];
    
    if( $("#academic_qualifications > tbody").find("tr").length > 0 )
    {
        for(var i=1; i<row_id; i++)
        {
            for(var j=0; j<elemArray.length; j++)
            {    
                if( !$('#'+elemArray[j]+i).val() )
                {
                    if (elemArray[j] === 'academicfiles_c_' ||  elemArray[j] === 'academicfiles_t_')
                    {
                        $('#'+elemArray[j]+i).css({"border-color": "red", "border":"1px", "border-style":"solid"});
                    }
                    
                    highlightColumn(elemArray[j]+i);
                    noAcademicValue = true;
                }
                else
                {
                    if (elemArray[j] === 'academicfiles_c_' ||  elemArray[j] === 'academicfiles_t_')
                    {
                        $('#'+elemArray[j]+i).css({"border-color": "white", "border":"0px", "border-style":"solid"});
                    }
                    resetColumn(elemArray[j]+i);
                }
            }
        }
    }
    
    if (noAcademicValue)
    {
        return false;
    }
    else
    {
        return true;
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

function doFormSubmit(elemID)
{
    requiredFields.length = 0;

    var errCnt = 0;
    var frm = document.userManagerForm;

    // Setup required fields
    setupForm(frm, elemID);
    
    // Validate form for required fields
    errCnt = validateForm(frm);

    if (errCnt)
    {
        alert(MISSING_REQUIRED_FIELDS);
        return false;
    }
    else
    {
        // if validateFields() returns true then submit the form
        if(validateFields(frm))
        {
            //return true;
            if (elemID === 'personal-info')
            {
                frm.cmd.value = 'personal-info';
                frm.next_tab.value = 'academic-info';
            }
            else if (elemID === 'ticket-info')
            {
                frm.cmd.value = 'ticket-info';
                frm.next_tab.value = 'ticket-info';
            }
            else if (elemID === 'academic-info')
            {
                frm.cmd.value = 'academic-info';
                frm.next_tab.value = 'university-info';
            }
            else if(elemID === 'university-info')
            {
                frm.cmd.value = 'university-info';
                frm.next_tab.value = 'ticket-info';
            }

            frm.submit();
        }
    }
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

function populateAcademicDetails(id, uid, degree, attachmentname_c, file_location_c, doc_id_c, result, attachmentname_t, file_location_t, doc_id_t)
{
    var elemID = row_id-1;

    $('#degree_'+elemID).val(degree);
    $('#result_'+elemID).val(result);
    $('#aqid_'+elemID).val(id);
    $('#attachmentname_c_'+elemID).val(attachmentname_c);
    $('#attachmentname_t_'+elemID).val(attachmentname_t);
    $('#id_c_'+elemID).attr('href',file_location_c );
    $('#id_t_'+elemID).attr('href',file_location_t );
    $('#file_location_c_'+elemID).val(file_location_c);
    $('#file_location_t_'+elemID).val(file_location_t);
    
    if (doc_id_c > 0)
    {
        $('#id_c_'+elemID).show();
    }
    if (doc_id_t > 0)
    {
        $('#id_t_'+elemID).show();
    }
}

function addNewRow()
{
    var td_empty              = '<td width="4" class="prepand">&nbsp;</td>';
    var td_degree             = '<td class="prepand"> '+ getDegreeList() +'</td>';
    var td_result             = '<td class="prepand"><input type="text" name="result_'+row_id+'" id="result_'+row_id+'"    value="" class="inputbox3 W90" ></td>';  
    var td_attachmentname_c   = '<td class="prepand"><input type="text" name="attachmentname_c_'+row_id+'" id="attachmentname_c_'+row_id+'" value="" class="inputbox3 W165" placeholder="Certificate"></td>';  
    var td_attachmentname_t   = '<td class="prepand"><input type="text" name="attachmentname_t_'+row_id+'" id="attachmentname_t_'+row_id+'" value="" class="inputbox3 W165" placeholder="Result/Grade Sheet/Transcript"></td>';  
    var td_attachment_c       = '<td class="prepand" align="left"><input type="file" name="academicfiles_c_'+row_id+'" onchange=uploadFiles("academicfiles_c_'+row_id+'")   id="academicfiles_c_'+row_id+'" value="" class="W175" /></td>';
    var td_attachment_t       = '<td class="prepand" align="left"><input type="file" name="academicfiles_t_'+row_id+'" onchange=uploadFiles("academicfiles_t_'+row_id+'")   id="academicfiles_t_'+row_id+'" value="" class="W175" /></td>';
    var td_action             = '<td class="prepand" id="td_action_'+row_id+'" align="center"><a href="javascript: void(0);" onClick="deleteRow('+row_id+');"><img src="/app_contents/common/images/cross2.png"></a></td>';
    var td_view_c             = '<td class="prepand" id="td_view_'+row_id+'" align="center"><a id="id_c_'+row_id +'" href="" target="_new"><img src="/app_contents/common/images/view22.png"></a></td>';
    var td_view_t             = '<td class="prepand" id="td_view_'+row_id+'" align="center"><a id="id_t_'+row_id +'" href="" target="_new"><img src="/app_contents/common/images/view22.png"></a></td>';
    var hidden_field          = '<input type="hidden" id="aqid_'+row_id+'" name="aqid_'+row_id+'" value="" >\n\
                                 <input type="hidden" id="file_location_c_'+row_id+'" name="file_location_c_'+row_id+'" value="">\n\
                                 <input type="hidden" id="file_location_t_'+row_id+'" name="file_location_t_'+row_id+'" value="">';
     
    $('<tr id="tr_'+row_id+'" class="border"><td colspan="2"></td>'+td_attachmentname_t+td_view_t+td_attachment_t+'</tr>').prependTo("#academic_qualifications > tbody");
    $('<tr id="tr_'+row_id+'">'+td_degree+td_result+td_attachmentname_c+td_view_c+td_attachment_c+td_action+hidden_field+'</tr>').prependTo("#academic_qualifications > tbody")
    
    $('#id_c_'+row_id).hide();
    $('#id_t_'+row_id).hide();
    row_id++;    
    
}

function getDegreeList()
{
    var selectObj = '<select name="degree_'+row_id+'" id="degree_'+row_id+'" class="combo1 W110" onChange="checkDegree(this);">';
    
    for (var i = 0; i < degreeArray.length; i++)
    {
        selectObj += '<option value="'+degreeArray[i]+'">'+degreeArray[i]+'</option>';
    }
    
    return selectObj;    
}

function checkDegree(elem)
{
    alert(elem.value);
}

function deleteRow(elemID)
{
    var id       = $('#aqid_'+elemID).val();
    
    if ( doConfirm("Document will be deleted.\n" + PROMPT_DELETE_CONFIRM ) )
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
                        row_id--;
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
    var total = fare+tax;
    
    $('#total').val(total.toFixed(2));
    
}


function calculateFareUSD()
{
    var fare = $('#ticket_fare_usd').val()*1;
    var tax  = $('#tax_usd').val()*1;
    var total = fare+tax;
    
    $('#total_usd').val(total.toFixed(2))
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

function showTabs(thisField,currentTabId)
{
    hideAllTabs();
    
    $('#'+currentTabId).show();
    $('#'+thisField).css({'background-color':'#FFFFFF', 'border-left':'1px solid', 'border-top':'1px solid', 'color':'#0CA3D2'});
}

function hideAllTabs()
{
    $('#personal-info-content').hide();
    $('#academic-info-content').hide();
    $('#university-info-content').hide();
    $('#ticket-info-content').hide();
    
    $('#personal-info-tab').css({'background-color': '#0CA3D2', 'border-left':'0px', 'border-top':'0px', 'color':'white'});
    $('#academic-info-tab').css({'background-color': '#0CA3D2', 'border-left':'0px', 'border-top':'0px', 'color':'white'});
    $('#university-info-tab').css({'background-color': '#0CA3D2', 'border-left':'0px', 'border-top':'0px', 'color':'white'});
    $('#ticket-info-tab').css({'background-color': '#0CA3D2', 'border-left':'0px', 'border-top':'0px', 'color':'white'});
     
}

function loadCityByCountry(destination_airport)
{
    var country = $('#country').val();
    
    $.ajax
    (
        {
            url: 'application_manager.php?cmd=city',
            data: "country=" + country,
            dataType: 'json',
            success: function(responseText)
            {
                var cityArray = responseText.split(OUTER_DELIM);
                
                if (responseText)
                {
                    $("#destination_airport_dd").removeAttr("disabled");
                    $("#destination_airport_dd").show(); 
                    $('#destination_airport_dd').empty(); 
                    $('#destination_airport_dd').append($('<option>', { value: '', text : '' } ) );   
                    $("#destination_airport_tb").hide();
                    $("#destination_airport_tb").val(''); 
                    $("#destination_airport_tb").attr("disabled", "disabled");
                    $('#destination_airport_lbl').attr('id', 'destination_airport_dd_lbl');
                    
                    for(var i=0; i<cityArray.length; i++)
                    {
                        //alert(cityArray[i]);
                        $('#destination_airport_dd').append($('<option>', { value: cityArray[i], text : cityArray[i] } ) );   
                    }
                }
                else
                {
                    $("#destination_airport_dd").val(''); 
                    $("#destination_airport_dd").attr("disabled", "disabled"); 
                    $("#destination_airport_dd").hide(); 
                    $("#destination_airport_tb").show(); 
                    $("#destination_airport_tb").removeAttr("disabled");
                    $('#destination_airport_lbl').attr('id', 'destination_airport_tb_lbl');
                }
                $("#destination_airport_dd").val(destination_airport); 
            }    
        }
    );
}

function toggleDestinationAirport()
{
    if ( $('#list').attr('checked') )
    {
        $("#destination_airport_dd").val(''); 
        $("#destination_airport_dd").attr("disabled", "disabled"); 
        $("#destination_airport_dd").hide(); 
        $("#destination_airport_tb").show(); 
        $("#destination_airport_tb").removeAttr("disabled");
        $('#destination_airport_dd_lbl').attr('id', 'destination_airport_tb_lbl');
    }
    else
    {
        loadCityByCountry();
    }
}
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

function sendMail()
{
    //location.href = 'http://'+document.domain+'/app/applicant_manager/applicant_manager.php?cmd=sendmail';
    if ( doConfirm("Are you sure to send mail to accepted applicants?" ) )
    {
        $.ajax
        (
            {
                url: 'applicant_manager.php?cmd=sendmail',
                dataType: 'json',
                success: function(response)
                {
                     if (response == 1)
                     {
                         alert('Mail sent successfully');        
                     }
                }    
            }
        );
    }
    else
    {}
}

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
    location.href = 'http://'+document.domain+'/app/applicant_manager/applicant_manager.php';
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

var degreeArray = ["", "S.S.C","O Levels","H.S.C.", "A Levels", "Bachelor","Masters","Ph.D"];

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

function saveRemarks()
{
    var app_id  = $('#app_id').val();
    var remarks = $('#remarks').val();
    
    //alert(remarks)
    
    $.ajax
        (
           {
              url: 'applicant_manager.php?cmd=saveRemarks',                //the script to call to get data          
              data: "app_id="+app_id+'&remarks='+escape(remarks),
              dataType: 'json',                                        //data format      
              success: function(response)                          //on recieve of reply
              {
                       
              }    
           }
        );
}
function acceptApp(appID)
{
    
    $.ajax
        (
           {
              url: 'applicant_manager.php?cmd=acceptApplication',                //the script to call to get data          
              data: "id="+appID,
              dataType: 'html',                                        //data format      
              success: function(response)                          //on recieve of reply
              {
                       $('#application_status_'+appID).html('ACCEPTED');
                       $('#application_status_'+appID).removeClass('reject-label');
                       $('#application_status_'+appID).removeClass('pending-label');
                       $('#application_status_'+appID).addClass('accept-label');
                       
                       jQuery.fancybox.close();
                       $('#message-body').html('<div id="message" class="success">Application Status has been change successfully</div>');
                       setTimeout(function(){
                            $('#message').fadeOut("slow", function () {
                            $('#message').removeClass('success');
                                });

                          }, 2500);
              }    
           }
        );
}

function rejectApp(appID)
{
    $.ajax
        (
           {
              url: 'applicant_manager.php?cmd=rejectApplication',                //the script to call to get data          
              data: "id="+appID,
              dataType: 'html',                                        //data format      
              success: function(response)                          //on recieve of reply
              {  
                   $('#application_status_'+appID).html('REJECTED');
                   $('#application_status_'+appID).removeClass('accept-label');
                   $('#application_status_'+appID).removeClass('pending-label');
                   $('#application_status_'+appID).addClass('reject-label');
                   jQuery.fancybox.close();
                   $('#message-body').html('<div id="message" class="error">Application Status has been change successfully</div>');
                       setTimeout(function(){
                            $('#message').fadeOut("slow", function () {
                            $('#message').removeClass('error');
                                });

                          }, 2000);        
              }    
           }
        );
}

function acceptAll()
{
     var i,f=0,ids='';
     var selectedId = [];
     
        for(i=0;i<AppIDs.length;i++)
        {
            if(document.getElementById('app_id_'+AppIDs[i]).checked == true)
            {
               if(f) ids = ids+',';
               
               ids = ids+AppIDs[i];
               selectedId.push(AppIDs[i]);
               f=1;
            }
        }
        
        $.ajax
        (
           {
              url: 'applicant_manager.php?cmd=acceptall',                //the script to call to get data          
              data: "ids="+ids,
              dataType: 'html',                                        //data format      
              success: function(response)                          //on recieve of reply
              {
                    
                    for(i=0;i<selectedId.length;i++)
                    {    
                       $('#application_status_'+selectedId[i]).html('ACCEPTED');
                       $('#application_status_'+selectedId[i]).removeClass('reject-label');
                       $('#application_status_'+selectedId[i]).removeClass('pending-label');
                       $('#application_status_'+selectedId[i]).addClass('accept-label');
                       
                       $('#message-body').html('<div id="message" class="success">Application Status has been change successfully</div>');
                       setTimeout(function(){
                            $('#message').fadeOut("slow", function () {
                            $('#message').removeClass('success');
                                });

                          }, 2000);
                    }        
              }    
           }
        );
     
}

function rejectAll()
{
     var i,f=0,ids='';
     var selectedId = [];
     
        for(i=0;i<AppIDs.length;i++)
        {
            if(document.getElementById('app_id_'+AppIDs[i]).checked == true)
            {
               if(f) ids = ids+',';
               
               ids = ids+AppIDs[i];
               selectedId.push(AppIDs[i]);
               f=1;
            }
        }
        
        $.ajax
        (
           {
              url: 'applicant_manager.php?cmd=rejectall',                //the script to call to get data          
              data: "ids="+ids,
              dataType: 'html',                                        //data format      
              success: function(response)                          //on recieve of reply
              {
                    for(i=0;i<selectedId.length;i++)
                    {    
                       $('#application_status_'+selectedId[i]).html('REJECTED');
                       $('#application_status_'+selectedId[i]).removeClass('accept-label');
                       $('#application_status_'+selectedId[i]).removeClass('pending-label');
                       $('#application_status_'+selectedId[i]).addClass('reject-label');
                       
                      
                       $('#message-body').html('<div id="message" class="error">Application Status has been change successfully</div>');
                       setTimeout(function(){
                            $('#message').fadeOut("slow", function () {
                            $('#message').removeClass('success');
                                });

                          }, 2000);
                    }
                            
              }    
           }
        );
     
}



function doApplicantSearch()
{
    var source                = document.getElementById('applicantFrame').src;
    var applicant_name        = $('#applicant_name').val();
    var app_id                = $('#app_id').val();
    var email                 = $('#email').val();
    var country               = $('#country').val();
    var application_status    = $('#application_status').val();
    var gender                = $('#gender').val();
    var guardian_income_min   = $('#guardian_income_min').val();
    var guardian_income_max   = $('#guardian_income_max').val();
    var session_year          = $('#session_year').val();
    var degree_list           = '';
    
    $("input[name='degree']").each( function () 
    {
       if ($(this).attr('checked'))
       {
           if (degree_list) degree_list += ', ';
           degree_list += '\'' + $(this).val() + '\'';
       }
    });
    
    var export_to_url = '?cmd=excel&applicant_name='+applicant_name+'&email='+email+'&country='+country+'&gender='+gender+'&degree='+degree_list+
                        '&application_status='+application_status+'&guardian_income_min='+guardian_income_min+
                        '&guardian_income_max='+guardian_income_max+'&session_year='+session_year;
    
    $('#export_to').attr('href', export_to_url);
    $('#applicantFrame').attr('src', source+'&app_id='+app_id+'&applicant_name='+applicant_name+'&email='+email+'&country='+country+
                                            '&application_status='+application_status+'&gender='+gender+'&guardian_income_max='+guardian_income_max+
                                            '&guardian_income_min='+guardian_income_min+'&degree='+degree_list+'&session_year='+session_year);//'&received_grant='+received_grant);
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
                    //parent.$.fancybox.open
                    $.fancybox.open
                    (
                        {
                            'href'            : '#std_details_'+elemID,
                            'titleShow'       : false,
                            'transitionIn'    : 'elastic',
                            'transitionOut'   : 'elastic',
                            'autoDimensions'  : false,
                            'autoSize'        : false,
                            'allowfullscreen' : 'true',
                            'topRatio'        : 0,
                            'width'           : 620,
                            'height'          : 750,
                            'padding'         : 0
                        },
                        {
                            helpers : 
                            {
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
            }
        );
}

function showTabs(tabID,appID)
{ 
    var i =0;
    
    for(i=1;i<=4;i++)
    {
       $('#tabs-'+i+'_'+appID).hide(); 
       $('#tab'+i+'_'+appID).css('background-color','#0CA3D2');
    }
   
    
    $('#tabs-'+tabID+'_'+appID).show();
    
    $('#tab'+tabID+'_'+appID).css('background-color','#484789');
    
}


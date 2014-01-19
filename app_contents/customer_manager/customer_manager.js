/*
 *   File: customer_manager.js
 *
 */

RE_NAME          = new RegExp(/[^A-Z^a-z^ ^\.\^]$/);
RE_EMAIL         = new RegExp(/^[A-Za-z0-9](([_|\.|\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([_|\.|\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]{2,})$/);
RE_USERNAME      = new RegExp(/^[a-z0-9\_]+$/);
RE_DECIMAL       = new RegExp(/^[0-9]{1,8}([\.]{1}[0-9]{1,2})?$/);
RE_NUMBER        = new RegExp(/^[0-9]+$/);
RE_PHONE         = new RegExp(/^((\d\d\d)|(\(\d\d\d\)))?\s*[\.-]?\s*(\d\d\d)\s*[\.-]?\s*(\d\d\d\d)$/);
RE_ZIP           = new RegExp(/^[0-9]{5}(([\-\ ])?[0-9]{4})?$/);
RE_UK_POSTCODE   = new RegExp(/^([A-PR-UWYZ0-9][A-HK-Y0-9][AEHMNPRTVXY0-9]?[ABEHMNPRVWXY0-9]? {0,2}[0-9][ABD-HJLN-UW-Z]{2}|GIR ?0AA)$/);

var duplicate_email         = false;
var duplicate_company_name  = false;

function getCapsuleInfo()
{
    var capsule_id = $('#capsule_id').val();
    
    //location.href = 'http://'+document.domain+'/app/customer_manager/customer_manager.php?cmd=capsule&capsule_id='+capsule_id;

    $.ajax
    (
        {                                      
            url: 'customer_manager.php?cmd=capsule',           //the script to call to get data          
            data: "capsule_id="+capsule_id,                    //you can insert url argumnets here to pass to api.php   //for example "id=5&parent=6"
            dataType: 'json',                                  //data format      
            success: function(responseText)                    //on recieve of reply
            {
                // check whether the data type is 'Organisation' or 'Person'. If organisation then ok
                // else show an alert message 
                if (responseText == '')
                {
                    clearCustomerDetailsFields();
                    alert("No organisation found. Please try with a valid organisation id.");
                }
                else if ( responseText.type == 'Organisation')
                {
                    //alert(responseText.company_name)
                    //$('#first_name').val(responseText.first_name);
                    //$('#last_name').val(responseText.last_name);
                    $('#company_name').val(responseText.company_name);
                    $('#email').val(responseText.email);
                    $('#address').text(responseText.address);
                    $('#town').val(responseText.town);
                    $('#county').val( selectCounty(responseText.county) );
                    $('#postcode').val(responseText.postcode);
                    
                    isCompanyExists();
                    checkDuplicateEmail();
                }
                else if ( responseText.type == 'Person')
                {
                    clearCustomerDetailsFields();
                    alert("You have entered an id of a contact. Only organisations can be imported.");
                }
            }
        } 
    );
}

function clearCustomerDetailsFields()
{
    //$('#first_name').val('');
    //$('#last_name').val('');
    $('#company_name').val('');
    $('#email').val('');
    $('#address').text('');
    $('#town').val('');
    $('#county').val('');
    $('#postcode').val('');
}

function selectCounty(countyVal)
{
    var ddl = document.getElementById('county');

    for (i = 0; i < ddl.options.length; i++) 
    {
       //alert(ddl.options[i].text);
       if ( ddl.options[i].text == countyVal )
       {
           //alert('County = ' + countyVal +'\n Op Value = '+ddl .options[i].value);
           return ddl.options[i].value;
       }
    }
}

function openFancyBox()
{
    $.fancybox.open
    (
        [
            {
                'href'   : '#capsule_customer',
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


function setupForm(frm)
{
    with (frm)
    {
        setRequiredField(company_name,  'textbox',   'company_name');
        setRequiredField(email,         'textbox',   'email');
    }
}

function validateFields(frm)
{
    with(frm)
    {
        if (!RE_EMAIL.exec(email.value))
        {
            highlightTableColumn('email');
            alert(ERROR_EMAIL);
            return false;
        }

        return true;
    }
}

function doFormSubmit()
{
    requiredFields.length = 0;

    var errCnt = 0;
    var frm = document.customerForm;
    
    // Setup required fields
    setupForm(frm);

    // Validate form for required fields
    errCnt = validateForm(frm);

    if (errCnt)
    {
        alert(MISSING_REQUIRED_FIELDS);
        return false;
    }
    else if (duplicate_company_name)  // check whether the company exists or not
    {
        highlightTableColumn('company_name');
        highlightTableColumn('email');
        highlightTableColumn('address');
        
        alert(DUPLICATE_COMPANY_NAME);
        return false;
    }
    else if (duplicate_email)  // for duplicate email of company
    {
        highlightTableColumn('email');
        alert(DUPLICATE_EMAIL);
        duplicate_email = true;
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

function doCustomerSearch()
{
    var source         = document.getElementById('customerFrame').src;
    var company_name   = $('#company_name_search').val();
    var customer_name  = $('#customer_name').val();
    var status         = $('#status').val();
    
    //$('#customerFrame').attr('src', source+'&customer_name='+customer_name+'&company_name='+company_name + '&status='+status);
    $('#customerFrame').attr('src', source+'&company_name='+company_name + '&status='+status);
}

function doResetForm(frm)
{
    $("#customerForm")[0].reset();
    $('#address').text('');
}

function doClearCustomerImportForm()
{
    $('#capsule_id').val('');
}

function doClearForm()
{
    $('#customer_name').val('');
    $('#company_name').val('');
    $('#status').val('');
    
    location.href = 'http://'+document.domain+'/app/customer_manager/customer_manager.php';
}

function isNumberKey(evt) 
{
   var charCode = (evt.which) ? evt.which : evt.keyCode;
   
   if(charCode == 46 || charCode == 8 || charCode == 9)
      return true;
      
   if (charCode < 48 || charCode > 57)
      return false;
   
   return true;
}  

function isCompanyExists()
{
    var company_name = $('#company_name').val();
    
    $.ajax
    (
        {                                      
            url: 'customer_manager.php?cmd=chkcompany',         //the script to call to get data          
            data: "company_name="+company_name,                 //you can insert url argumnets here to pass to api.php   //for example "id=5&parent=6"
            dataType: 'json',                                   //data format      
            success: function(responseText)                     //on recieve of reply
            {
                if ( responseText != '')  // 1 means duplicate found
                {
                    duplicate_company_name = true;
                    alert("Record already exists.");
                    highlightTableColumn('company_name');
                    return true;
                }
                else
                {
                    resetTableColumn('company_name');
                    duplicate_company_name = false;
                    return false;
                }
            }
        } 
    );    
}

function checkDuplicateEmail()
{
    var email = $('#email').val();

    $.ajax
    (
        {                                      
            url: 'customer_manager.php?cmd=chkemail',           //the script to call to get data          
            data: "email="+email,                               //you can insert url argumnets here to pass to api.php   //for example "id=5&parent=6"
            dataType: 'json',                                   //data format      
            success: function(responseText)                     //on recieve of reply
            {
                if ( responseText != '')
                {
                    //highlightTableColumn('email');
                    //alert(DUPLICATE_EMAIL);
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

function checkEmpty(elemID)
{
    var elemVal = $('#'+elemID).val();
    
    if (elemVal == '' || elemVal == 0)
    {
        $('#'+elemID).val('0.00');
    }
}

function doUpperCase(obj) 
{
    obj.value = obj.value.toUpperCase();
}

function checkCustmerOrderStatus(custID)
{
    $.ajax
    (
        {                                      
            url: 'customer_manager.php?cmd=custstatus',         //the script to call to get data          
            data: "id=" + custID,                               //you can insert url argumnets here to pass to api.php   //for example "id=5&parent=6"
            dataType: 'json',                                   //data format      
            success: function(responseText)                     //on recieve of reply
            {
                if ( responseText != '')
                {
                    var msg = 'This customer have ' + responseText + '.\n';
                }
                else
                {
                }
            }
        } 
    );  
        
    return doConfirm(msg+PROMPT_DELETE_CONFIRM);
}

function gotoCustomerOrder(customer_id)
{
    location.href = 'http://'+document.domain+'/app/order_manager/order_manager.php?customer_id='+customer_id+'&cmd=edit';
}
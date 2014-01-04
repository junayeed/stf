/*
 *   File: product_manager.js
 *
 */

RE_NAME     = new RegExp(/[^A-Z^a-z^ ^\.\^]$/);
RE_EMAIL    = new RegExp(/^[A-Za-z0-9](([_|\.|\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([_|\.|\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]{2,})$/);
RE_USERNAME = new RegExp(/^[a-z0-9\_]+$/);
RE_DECIMAL  = new RegExp(/^[0-9]{1,8}([\.]{1}[0-9]{1,3})?$/);
RE_NUMBER   = new RegExp(/^[0-9]+$/);
RE_PHONE    = new RegExp(/^((\d\d\d)|(\(\d\d\d\)))?\s*[\.-]?\s*(\d\d\d)\s*[\.-]?\s*(\d\d\d\d)$/);
RE_ZIP      = new RegExp(/^[0-9]{5}(([\-\ ])?[0-9]{4})?$/);

var duplicate_product_code = false;
var productOptnArray = [];

function updateProductOptions(elemID)
{
    var elemValue = $('#'+elemID).val();
    
    if ( $('#'+elemID).is(':checked') )
    {
        //alert($('#'+elemID).val());
        productOptnArray.push( "'"+elemValue+"'" );
    }
    else
    {
        productOptnArray.splice(productOptnArray.indexOf("'"+elemValue+"'"), 1)
    }
}


function changeOptions(elemValue)
{
    if (elemValue == 'Artwork')
    {
        // hide space allocated(qty_per_page)
        $('#qty_per_unit_lbl').hide();
        // show size and shape
        $('#size_shape_lbl').show();
        
        resetTableColumn('qty_per_unit');
    }
    else if (elemValue == 'Schedule')
    {
        // hide size and shape
        $('#size_shape_lbl').hide();
        // show space allocated(qty_per_page)
        $('#qty_per_unit_lbl').show();
        
        resetTableColumn('qty_per_unit');
    }
    
    // if product_option has no value that is the empyt form is loading then set the default value i.e. 'Schedule'
    if ( !elemValue)
    {
        $('#product_option_schedule').attr("checked", true);    
    }
}

function setupForm(frm)
{
   with (frm)
   {
      setRequiredField(product_code,    'textbox',   'product_code');
      setRequiredField(description,     'textbox',   'description');
      setRequiredField(qty_per_unit,    'textbox',   'qty_per_unit');
      setRequiredField(unit_price,      'textbox',   'unit_price');
      //setRequiredField(product_group,   'textbox',   'product_group');
   }
}

function validateFields(frm)
{
    with(frm)
    {
        if($('#product_option_schedule').is(':checked'))
        {
            if (!RE_DECIMAL.exec(qty_per_unit.value))
            {
                highlightTableColumn('qty_per_unit');
                alert('Please enter a valid number. SCHEDULE');
                return false;
            }

            return true;
        }
        else if($('#product_option_artwork').is(':checked'))
        {
            if (!RE_DECIMAL.exec(size_shape.value))
            {
                highlightTableColumn('size_shape');
                alert('Please enter a valid number. ARTWORK');
                return false;
            }

            return true;
        }
        else
        {
            return true;
        }
    }
}

function doFormSubmit()
{
    requiredFields.length = 0;

    var errCnt = 0;
    
    var frm = document.productForm;

    // Setup required fields
    setupForm(frm);

    // Validate form for required fields
    errCnt = validateForm(frm);

    if (errCnt)
    {
        alert(MISSING_REQUIRED_FIELDS);
        return false;
    }
    else if (duplicate_product_code)  // for duplicate product code
    {
        highlightTableColumn('product_code');
        alert(DUPLICATE_PRODUCT_CODE);
        duplicate_product_code = true;
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

function doProductSearch()
{
    var source          = document.getElementById('productsFrame').src;
    var magazine_code   = $('#magazine_code').val();
    var product_size    = $('#product_size').val();
    var product_option  = $('#options').val();
    
    $('#productsFrame').attr('src', source+'&magazine_code='+magazine_code+'&product_size='+product_size+'&options='+product_option);
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

function checkProductCode()
{
    var product_code = $('#product_code').val();

    $.ajax
    (
        {                                      
            url: 'product_manager.php?cmd=chkproductcode',                    //the script to call to get data          
            data: "product_code="+product_code,                               //you can insert url argumnets here to pass to api.php   //for example "id=5&parent=6"
            dataType: 'json',                                         //data format      
            success: function(responseText)                           //on recieve of reply
            {
                if ( responseText != '')
                {
                    highlightTableColumn('product_code');
                    alert(DUPLICATE_PRODUCT_CODE);
                    duplicate_product_code = true;
                    return false;
                }
                else
                {
                    resetTableColumn('product_code');
                    duplicate_product_code = false;
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

function doResetForm()
{
    $('#productForm')[0].reset();
}
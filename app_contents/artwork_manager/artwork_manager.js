/*
 *   File: artwork_manager.js
 *
 */

RE_NAME     = new RegExp(/[^A-Z^a-z^ ^\.\^]$/);

var itemStatusArray  = new Array('With Designer', 'Proof on file', 'Awating Client', 'Offer Change', 'Approved', 'Filed', 'Archived');
var itemShapeArray   = new Array('L', 'P');
var months           = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
var row_id           = 1;
var INNER_DELIM      = '$$$';
var OUTER_DELIM      = '###';
var SEPARATOR        = '_';
var DATE_SEPARATOR   = '-';
var VAT              = 0.20;
var target_elemID;

function gotoURL(URLtext)
{
    var customer_id = $('#customer_id').val();
    location.href = 'http://'+document.domain+'/app/'+URLtext+'/'+URLtext+'.php?cmd=edit&customer_id='+customer_id;
}

function doCancelOrder()
{
    var customer_id = $('#customer_id').val();
    location.href = 'http://'+document.domain+'/app/order_manager/order_manager.php';
}

/**
 * set up the form for validation
 * @param {form} frm
 * @returns {undefined}
 */

function setupForm(frm) 
{ 
    with (frm)
    {
        //setRequiredField(autocomplete,     'textbox',   'customer_name');
    }
}

/**
 * validate the form with the regular expression 
 * @param {form} frm
 * @returns {Boolean}
 */

function validateFields(frm)
{
    with(frm)
    {
        if (RE_NAME.exec(autocomplete.value))
        {
            highlightTableColumn('autocomplete');
            highlightTableColumn('customer_name_lbl');
            alert(MISSING_REQUIRED_FIELDS);
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
        else if ( !RE_UK_POSTCODE.exec(postcode.value) )
        {
            highlightTableColumn('postcode');
            alert(ERROR_UK_ZIPCODE);
            return false;
        }

        return true;
    }
}

/**
 * saubmit the form after the form validation and regular expression 
 * @returns {Boolean}
 */

function doFormSubmit()
{
    requiredFields.length = 0;

    var errCnt = 0;
    var frm = document.orderManagerForm;

    if ( frm.autocomplete.value == '')
    {
        highlightTableColumn('autocomplete');
        alert(MISSING_REQUIRED_FIELDS);
        return false;
    }
    
    return true;
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
    var dt          = new Date();
    var start_year  = 2013;
    var end_year    = start_year+2;
    
    var selectObj = '<select name="'+elemName+'_'+row_id+'" id="'+elemName+'_'+row_id+'" class="combo1 W78" disabled>';
    
    if (elemName == 'end_month')
    {
        selectObj = selectObj + '<option value="Ongoing">Ongoing</option>';
    }
    
    for(j=start_year; j<=end_year; j++)
    {    
        for (var i = 0; i < months.length ; i++)
        {
            yr2 = j.toString().substring(2);

            selectObj = selectObj + '<option value="'+yr2+padNumber(i%12+1, 2)+'">'+months[i%12]+' '+yr2+'</option>';
        }
    }
    
    selectObj = selectObj + '</select>';
    
    return selectObj;
}

function getYearList()
{
    $('#year').append($('<option>', { value: '', text : '' } ) );
    
    for (var i = 2010; i < 2025; i++)
    {
        $('#year').append($('<option>', { value: i.toString().substring(2), text : i } ) );    
    }
}

function toggle()
{
    if($('#ongoing').attr("checked") != 'checked')
    {
        $('#td_month').show();
        $('#td_year').show();
        
        return ;
    }
    
    $('#td_month').hide();
    $('#td_year').hide();
}

function populateMonth()
{
    var m_txt = $('#month option:selected').text();
    var m_val = $('#month').val();
    var y_txt = $('#year').val();
    var hidden_field = target_elemID.replace('show_', '');
    
    if($('#ongoing').attr("checked") != 'checked')
    {
        $('#'+target_elemID).val(m_txt + ' ' + y_txt );
        $('#'+hidden_field).val(y_txt + m_val );
    }
    else
    {
        $('#'+target_elemID).val('Ongoing');
        $('#'+hidden_field).val('Ongoing');
    }
    
    $.fancybox.close(); // ajaj
}


/**
 * Generate the status dropdown list 
 * @returns {String}
 */
function getStatusList()
{
    var selectObj = '<select name="artworkstatus_'+row_id+'" id="artworkstatus_'+row_id+'" class="combo1 W110">';
    
    for (var i = 0; i < itemStatusArray.length; i++)
    {
        selectObj += '<option value="'+itemStatusArray[i]+'">'+itemStatusArray[i]+'</option>';
    }
    
    selectObj += "</select>";
    
    
    return selectObj;
}

function getShapeList()
{
    var selectObj = '<select name="shape_'+row_id+'" id="shape_'+row_id+'" class="combo1 W40">';
    
    for (var i = 0; i < itemShapeArray.length; i++)
    {
        selectObj += '<option value="'+itemShapeArray[i]+'">'+itemShapeArray[i]+'</option>';
    }
    
    selectObj += "</select></div>";
    
    return selectObj;
}

/**
 * Return the current date in specific format
 * @returns {String}
 */
function getCurrentDate()
{
    var today  = new Date();
    var dd     = today.getDate();
    var mm     = today.getMonth()+1; //January is 0!
    var yyyy   = today.getFullYear();

    if(dd<10){ dd='0'+dd; } 
    if(mm<10){ mm='0'+mm; } 
    
    today = dd+'-'+mm+'-'+yyyy;
    
    return today;
}

/**
 * convert the MySQL date format to a specific format
 * @param {date} dt
 * @returns {String}
 */

function formatDate(dt)
{
    var dateArray = dt.split(DATE_SEPARATOR);
    
    //alert('Year ::: ' + dateArray[0] + '\n' + 'Month ::: ' + dateArray[1] + '\n' + 'Date ::: ' + dateArray[2]);
    return dateArray[2] + '-' + dateArray[1] + '-' + dateArray[0];
}

/**
 * Generate the Magazine dropdown list
 * @returns {String}
 */
function  getMagazineList()
{
    return '<select name="magazine_code_'+row_id+'" id="magazine_code_'+row_id+'" class="combo1" disabled>' + magazineOptns + '</select>';
}

/**
 * Populate the order details with the data from database
 * @param {int} product_details_id  * @param {int} product_id
 * @param {int} product_group  * @param {string} description
 * @param {int} magazine_code  * @param {string} start_month
 * @param {string} end_month  * @param {string} alternative
 * @param {int} qty  * @param {number} price
 * @param {number} discount  * @param {number} total
 * @param {string} status  * @param {string} placed_by
 * @param {date} order_date  * @param {string} page
 * @param {String} product_code
 * @returns none
 */

function populateOrderDetails(product_details_id, product_id, magazine_code, start_month, end_month, status, product_code, qty_per_unit, 
                              artwork_id, shape, product_status)
{
    var elemID = row_id-1; 
    
    $('#order_no_'+elemID).val(product_details_id);
    $('#product_id_'+elemID).val(product_code);
    $('#size_'+elemID).val(qty_per_unit);
    $('#magazine_code_'+elemID).val(magazine_code);
    $('#start_month_'+elemID).val(start_month);
    $('#end_month_'+elemID).val(end_month);
    $('#pid_'+elemID).val(product_id);
    $('#order_details_id_'+elemID).val(product_details_id);
    $('#artwork_id_'+elemID).val(artwork_id);
    $('#shape_'+elemID).val(shape);
    $('#size_shape_'+elemID).val(qty_per_unit);
    //$('#instruction_'+elemID).val(instruction);
    $('#instruction_'+elemID).val($('#instruction_text_'+product_details_id).text());
    $('#artworkstatus_'+elemID).val(status);
    
    $('#magazine_code_'+elemID).hide();
    
    $('#magazine_'+elemID).val($('#magazine_code_'+elemID+' option:selected').text());
    
    monthIndex = start_month.substring(2, 4);
    if (monthIndex != '10')
    {
        s_month  = months[start_month.substring(2, 4).replace('0', '')-1];
    }
    else 
    {
        s_month  = months[start_month.substring(2, 4)-1];
    }
    start_month = s_month + ' ' + start_month.substring(0, 2);
    
    $('#start_month_'+elemID).val(start_month);
    
    if (end_month != 'Ongoing')
    {
        monthIndex = end_month.substring(2, 4);
        if (monthIndex != '10')
        {
            s_month  = months[end_month.substring(2, 4).replace('0', '')-1];
        }
        else 
        {
            s_month  = months[end_month.substring(2, 4)-1];
        }
        
        end_month = s_month + ' ' + end_month.substring(0, 2);
    }
    
    $('#end_month_'+elemID).val(end_month);
    
    calculateItemValue(elemID); // after loading the values calculate the item value

    if( product_status == 'Archive')
    {
        
        makeThisRowReadonly(elemID);
    }
}

function makeThisRowReadonly(elemID)
{
    $('#shape_'+elemID).attr('disabled', 'disabled');
    $('#size_shape_'+elemID).attr('disabled', 'disabled');
    $('#artworkstatus_'+elemID).attr('disabled', 'disabled');
    $('#instruction_'+elemID).attr('disabled', 'disabled');
    
    // add disable class to the fields
    $('#shape_'+elemID).addClass('DISABLE');
    $('#size_shape_'+elemID).addClass('DISABLE');
    $('#artworkstatus_'+elemID).addClass('DISABLE');
}

function showArtworkNotes(notes)
{
    //$('<div style="border: 1px solid green; ">'+notes+'</div>').appendTo("#order_tbl > tbody > #instruction_1");
    $("#order_tbl > tbody > #instruction_1").append('<div style="border: 1px solid green; ">'+notes+'</div>');
}

function showCalander(elemID)
{
    $('.date-picker').datepicker( 
    {
        closeText: 'OK', // Display text for close link
        prevText: '', // Display text for previous month link
        nextText: '',
        currentText: 'Ongoing',
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'M y',
        onClose: function(dateText, inst) 
        { 
            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).datepicker('setDate', new Date(year, month, 1));
            
            // update the hidden field
            var targetID = this.id.replace('show_', ''); //  get the element ID of hidden date field for start date/ end date
            $('#'+targetID).val( $('#'+this.id).val() ); // copy the date in the hidden field
        }
    });
}

/**
 * Add a new row after click on "Add a new row".
 * @returns none
 */

function addNewRow(new_row)
{
    var today           = getCurrentDate();

    var td_empty        = '<td width="4" class="prepand3">&nbsp;</td>';
    var td_order_no     = '<td width="85" class="prepand3"><input type="text" name="order_no_'+row_id+'" id="order_no_'+row_id+'" value="" class="inputbox3 noborder" readonly></td>';
    //var td_size         = '<td width="72" class="prepand3"><input type="text" name="size_'+row_id+'" id="size_'+row_id+'" value="" class="inputbox3 noborder" readonly></td>';  
    var td_magazine     = '<td width="68" class="prepand3">'+ getMagazineList() +'<input type="text" name="magazine_'+row_id+'" id="magazine_'+row_id+'" value="" class="inputbox3 noborder" readonly></td>';
    var td_start_month  = '<td width="82" class="prepand3"><input type="text" name="start_month_'+row_id+'" id="start_month_'+row_id+'" value="" class="inputbox3 noborder" readonly></td>';                                                                               // month column
    var td_end_month    = '<td width="82" class="prepand3"><input type="text" name="end_month_'+row_id+'" id="end_month_'+row_id+'" value="" class="inputbox3 noborder" readonly></td>';                                                                               // month column
    //var td_start_month  = '<td width="82" class="prepand3">'+ getMonthList('start_month') +'</td>';                                                                               // month column
    //var td_end_month    = '<td width="82" class="prepand3">'+ getMonthList('end_month') +'</td>';                                                                               // month column
    var td_shape        = '<td width="60" class="prepand3">'+ getShapeList() +'</td>'; // qty column
    var td_size_shape   = '<td width="190" class="prepand3"><input type="text" name="size_shape_'+row_id+'"        id="size_shape_'+row_id+'"          value="" class="inputbox3" style="width:91px;" readonly></td>'; // qty column
    var td_status       = '<td width="170" class="prepand3">'+ getStatusList() +'</td>';                                                        // status column
    var hidden_field    = '<input type="hidden" id="pid_'+row_id+'"              name="pid_'+row_id+'"             value="" >\n\
                           <input type="hidden" id="artwork_id_'+row_id+'"       name="artwork_id_'+row_id+'"      value="" >\n\
                           <input type="hidden" id="order_details_id_'+row_id+'" name="order_details_id_'+row_id+'" value="">';
     
    $('<tr class="reportOddRow" onmouseover="this.className=\'reportRowSelected\'" \n\
       onmouseout="this.className=\'reportOddRow\'" id="tr_'+row_id+'">'+td_empty+td_order_no+
       td_magazine+td_start_month+td_end_month+td_shape+td_size_shape+td_status+hidden_field+'</tr>').appendTo("#order_tbl > tbody");
    
    var td_instruction  = '<td valign="top" colspan="8"><div class="prepand2 border padding2" align="right"><textarea name="instruction_'+row_id+'" id="instruction_'+row_id+'" cols="" rows="" class="textarea" placeholder="Type Instruction Here"></textarea></div></td>'; // qty column    
    
    
    $('<tr class="reportOddRow" onmouseover="this.className=\'reportRowSelected\'" \n\
       onmouseout="this.className=\'reportOddRow\'" id="tr_'+row_id+'">'+td_empty+td_instruction+'</tr>').appendTo("#order_tbl > tbody");
    
    //var td_addBtn       = '<td><input type="button" class="button blue" name="add_note" value="Add Note" onClick="openInstructionBox();"></td>';
    //$('<tr id="tr_'+row_id+'">'+td_empty+td_addBtn+'</tr>').appendTo("#order_tbl > tbody");
    
    row_id++;    
}

function copyRow(srcID)
{
    addNewRow(1); // call add new row with a new order details ID
    elemID = parseInt(row_id-1);
    
    $('#product_id_'+elemID).val($('#product_id_'+srcID).val());
    $('#proddesc_'+elemID).val($('#proddesc_'+srcID).val());
    $('#magazine_'+elemID).val($('#magazine_'+srcID).val());
    $('#start_month_'+elemID).val($('#start_month_'+srcID).val());
    $('#end_month_'+elemID).val($('#end_month_'+srcID).val());
    $('#page_'+elemID).val($('#page_'+srcID).val());
    $('#qty_'+elemID).val($('#qty_'+srcID).val());
    $('#price_'+elemID).val($('#price_'+srcID).val());
    $('#discount_'+elemID).val($('#discount_'+srcID).val());
    $('#total_'+elemID).val($('#total_'+srcID).val());
    $('#productstatus_'+elemID).val($('#productstatus_'+srcID).val());
    //$('#placed_by_'+elemID).val($('#magazine_'+srcID).val());
    //$('#order_date_'+elemID).val(formatDate(order_date));
    $('#pid_'+elemID).val($('#pid_'+srcID).val());
    $('#order_details_id_'+elemID).val($('#order_no_'+elemID).val());
    
    if ($('#alternative_'+srcID).is(':checked'))
    {
        $('#alternative_'+elemID).attr("checked", true);
    }
    
    calculateItemValue(elemID); // after loading the values calculate the item value    
}

/**
 * Delete a specific row from DB by AJAX call and remove the row from the table
 * @param {int} elemID
 * @returns none
 */

function deleteOrderDetailsRow(elemID)
{
    //alert($("#order_tbl > tbody").find("#tr_"+elemID).length);
    var order_details_id = $('#order_no_'+elemID).val();
    var customer_id      = $('#customer_id').val();
    
    if ( doConfirm(" Order # " + order_details_id + " will be deleted.\n" + PROMPT_DELETE_CONFIRM ) )
    {
        $.ajax
        (
            {
                url: 'order_manager.php?cmd=deleteorder',                //the script to call to get data          
                data: "order_details_id="+order_details_id+"&customer_id="+customer_id,
                dataType: 'json',                                        //data format      
                complete: function(responseText)                          //on recieve of reply
                {
                    $("#order_tbl > tbody").find("#tr_" + elemID).fadeOut(1000,function() 
                    {
                        $('#order_tbl > tbody > #tr_' + elemID).remove();
                    });
                }    
            }
        );
    }
}

/**
 * Get the product list by AJAX call according the value of Product Group dropdown -- NOT IN USE
 * @param {int} elemID
 * @returns none
 */
function generateProductList(elemID)
{
    var prod_grp_id   = $('#productgroup_'+elemID + ' option:selected').val();
    
    if (prod_grp_id == '')
    {
        alert("Please select product group first.");
    }
    
    $('#prod_id_' + elemID).empty();
    
    $.ajax
    (
        {                                      
            url: 'order_manager.php?cmd=productlist',                //the script to call to get data          
            data: "pg_id="+prod_grp_id,
            dataType: 'json',                                        //data format      
            success: function(responseText)                          //on recieve of reply
            {
                if ( responseText != '')
                {
                    generateProductDropDownList(responseText, elemID);
                    //openProductBox(elemID);
                }
                else
                {
                }
            }
        } 
    );  
}

/**
 * Generate the product list dropdown with the AJAX call response -- NOT IN USE
 * @param {string} responseText
 * @param {int} elemID
 * @returns none
 */

function generateProductDropDownList(responseText, elemID)
{
    var productsArray = responseText.split(OUTER_DELIM);
    
    for(var i = 0; i<productsArray.length; i++)
    {
        itemArray = productsArray[i].split(INNER_DELIM);
        
        if (typeof(itemArray[1]) != "undefined")
        {
            $('#prod_id_' + elemID).append($('<option>', { value: itemArray[0], text : itemArray[1] } ) );
        }
    }
}

/**
 * Genarate the product table with the response of the AJAX call -- FUNCTION NO LONGER USE
 * @param {string} responseText
 * @param {int} elemID
 * @returns none
 */
function generateProductTable(responseText, elemID)
{
    var productsArray = responseText.split(OUTER_DELIM);
    
    //$('#product_tbl > tr').remove();
    $('#product_tbl').find("tr").remove(); // remove row
    
    for(var i = 0; i<productsArray.length; i++)
    {
        itemArray = productsArray[i].split(INNER_DELIM);
        
        if (typeof(itemArray[1]) != "undefined")
        {
            var td_product_code = '<td><a href="javascript:void(0);" onClick="mapProductDetails('+itemArray[0]+', '+elemID+');">'+itemArray[1]+'</a></td>';
            var td_product_name = '<td>' + itemArray[2] + '</td>';
            var td_price = '<td align="right">£ '+ parseFloat(itemArray[3]).toFixed(2) + '</td>';
            $('#product_tbl > tbody:last').append('<tr id="items_row" class="reportEvenRow" onmouseover="this.className=\'reportRowSelected\'" \n\
                                             onmouseout="this.className=\'reportEvenRow\'">'+td_product_code+td_product_name+td_price+'</tr>');
        }
    }
}

/**
 * Get the product details -- NOT IN USE
 * @param {int} elemID
 * @returns none
 */
function getProductDetails(elemID, proructID)
{
    //alert('ID ::: ' + elemID);
    //var itemText = $('#item_list option:selected').text();
    //var itemID   = $('#item_list option:selected').val();
    
    //alert('getProductDetails(elemID) = '+elemID +'\nText = ' + itemText + '\nID = ' + itemID);
    $('#code_'+elemID).val(itemText);
    $('#product_list').hide();
    $('#item_list').remove();
    
    mapProductDetails(elemID);
}

/**
 * Map the product details with a specific product upon selecting a product from the dropdown
 * @param {int} productID
 * @param {int} elemID
 * @returns none
 */

function mapProductDetails(productID, elemID)
{
    $.ajax
    (
        {                                      
            url: 'order_manager.php?cmd=productdetails',
            data: "id="+productID,
            dataType: 'json',
            success: function(responseText)
            {
                if ( responseText != '')
                {
                    item_details = responseText.split(INNER_DELIM);
                    //$('#product_id_'+elemID).val(item_details[0]);
                    $('#proddesc_'+elemID).val(item_details[2]);
                    $('#qty_'+elemID).val(1);
                    $('#price_'+elemID).val(item_details[4]);
                    $('#magazine_code_'+elemID).val(item_details[5]);
                    $('#pid_'+elemID).val(productID);
                    $('#order_details_id_'+elemID).val($('#order_no_'+elemID).val());
                    calculateItemValue(elemID);
                }
                else
                {
                }
            }
        } 
    );  
}

/**
 * Calculate the item total value by qty*price
 * @param {int} elemID
 * @returns none
 */
function calculateItemValue(elemID)
{
    var qty    = $('#qty_'+elemID).val()*1;
    var price  = $('#price_'+elemID).val()*1;
    
    $('#total_'+elemID).val(qty*price);
    
    calculateDiscount(elemID);
}

/**
 * Calculate the discount value
 * @param {int} elemID
 * @returns none
 */
function calculateDiscount(elemID)
{
    var discount    = $('#discount_'+elemID).val();
    var qty         = $('#qty_'+elemID).val()*1;
    var item_price  = $('#price_'+elemID).val()*1;
    var total       = qty * item_price;
    
    if ( !discount )
    {
        $('#total_'+elemID).val( total.toFixed(2) );
        return;
    }
    discount    = discount/100;
    
    $('#total_'+elemID).val( (total - (qty*item_price*discount)).toFixed(2) );
    //calculateTotal();
}

/**
 * Open a fancy box with the product list table
 * @param {string} elemID
 * @returns none
 */
function openInstructionBox()
{
    target_elemID = targetID;

    $.fancybox.open
    (
        [
            {
                'href'   : '#artwork_notes',
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

function saveArtworkNote()
{
    var customer_id = $('#customer_id').val();
    var notes       = $('#instructions').val().replace(/\r\n|\r|\n/g,"\n");
    
    $.ajax
    (
        {                                      
            url: 'artwork_manager.php?cmd=notes',
            data: "customer_id="+customer_id+'&artwork_notes='+notes,
            dataType: 'json',
            success: function(responseText)
            {
                if ( responseText != '')
                {
                    $('#instructions_lbl').get(0).innerHTML = notes; 
                    $.fancybox.close();
                }
                else
                {
                }
            }
        } 
    );      
}
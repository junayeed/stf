/*
 *   File: order_manager.js
 *
 */

RE_NAME     = new RegExp(/[^A-Z^a-z^ ^\.\^]$/);

var itemStatusArray  = new Array('B', 'C', 'P');
var months           = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
var row_id           = 1;
var INNER_DELIM      = '$$$';
var OUTER_DELIM      = '###';
var SEPARATOR        = '_';
var DATE_SEPARATOR   = '-';
var VAT              = 0.20;
var target_elemID;
var new_orders       = [];
var notsaved         = false;

//$(window).bind('beforeunload', function(){
//     
//    if( notsaved )
//    {
//        //window.onbeforeunload = "Ditto365";
//        return "It looks like you have input you haven't submitted."
//    }
//});

function gotoURL(URLtext)
{
    var customer_id = $('#customer_id').val();
    location.href = 'http://'+document.domain+'/app/'+URLtext+'/'+URLtext+'.php?cmd=edit&customer_id='+customer_id;
}

function doCancelOrder()
{
    var customer_id = $('#customer_id').val();

    location.href = 'http://'+document.domain+'/app/order_manager/order_manager.php?cmd=cancel&customer_id='+customer_id+'&new_order_ids='+new_orders;
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
 * @returns {String} -- the select box with given element name
 */

function getMonthList(elemName)
{
    var dt          = new Date();
    var start_year  = 2013;
    var end_year    = start_year+2;
    
    var selectObj = '<select name="'+elemName+'_'+row_id+'" id="'+elemName+'_'+row_id+'" class="combo1 W78" onChange="compareDate('+row_id+');">';
    
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

/**
 * Compare two month 
 * @param {int} the ID of the row where the start date and end date are
 * @returns {None}
 */

function compareDate(row_id)
{
    var start_month = $('#start_month_'+row_id).val();
    var end_month   = $('#end_month_'+row_id).val();
    
    resetColumn('start_month_'+row_id);
    resetColumn('end_month_'+row_id);
    
    //alert("Row ID ::: " + row_id + "\nStart Month ::: " + start_month + "\nEnd Month ::: " + end_month);
    
    if (end_month === 'Ongoing')
    {
        return;
    }
    else if(start_month > end_month)
    {
        alert("Order # " + $('#order_no_'+row_id).val() + "\nEnd Month should be greater than or equal to Start Month");
        highlightColumn('start_month_'+row_id);
        highlightColumn('end_month_'+row_id);
    }
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
    var selectObj = '<select name="productstatus_'+row_id+'" id="productstatus_'+row_id+'" class="combo1 W45">';
    
    for (var i = 0; i < itemStatusArray.length; i++)
    {
        selectObj += '<option value="'+itemStatusArray[i]+'">'+itemStatusArray[i]+'</option>';
    }
    
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
function getMagazineList()
{
    return '<select name="magazine_code_'+row_id+'" id="magazine_code_'+row_id+'" class="combo1 W60">' + magazineOptns + '</select>';
}

/**
 * Generate the product dropdown list
 * @returns {String}
 */
function getProductsList()
{
    return '<select name="prod_id_'+row_id+'" id="prod_id_'+row_id+'" class="inputbox3" style="width:35px;" onChange="mapProductDetails(this.value, '+row_id+');"><option value="">Please select product group first</option></select>';
}

/**
 * Load the product list according to a specific product group
 * @param {int} product_group
 * @returns {none}
 */
function loadProductList(product_group)
{
    productsArray = product_str.split(OUTER_DELIM);
    for(var i=0; i<productsArray.length; i++)
    {
        product = productsArray[i].split(INNER_DELIM)
        //alert('Value ::: ' + product[0] + ' Product Group ::: ' + product_group);
        if (product[0] == product_group)
        {
            $('#prod_id_'+parseInt(row_id-1)).append($('<option>', { value: product[1], text : product[2] } ) );    
        }
    }
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

function populateOrderDetails(product_details_id, product_id, product_group, description, magazine_code, start_month, end_month, 
                              alternative, qty, price, discount, total, status, placed_by, order_date, page, product_code, product_status)
{
    var elemID = row_id-1;
    
    $('#order_no_'+elemID).val(product_details_id);
    $('#product_id_'+elemID).val(product_code);
    $('#proddesc_'+elemID).text(description);
    $('#magazine_code_'+elemID).val(magazine_code);
//    $('#page_'+elemID).val(page);
    $('#qty_'+elemID).val(qty);
    $('#price_'+elemID).val(price);
    $('#discount_'+elemID).val(discount);
    $('#total_'+elemID).val(total);
    $('#productstatus_'+elemID).val(status);
    $('#placed_by_'+elemID).val(placed_by);
    $('#order_date_'+elemID).val(formatDate(order_date));
    $('#pid_'+elemID).val(product_id);
    $('#order_details_id_'+elemID).val(product_details_id);
    
    if (alternative == 'Yes')
    {
        $('#alternative_'+elemID).attr("checked", true);
    }
    
    //var s_month  = months[start_month.substring(2, 4).replace('0', '')-1];
    //start_month = s_month + ' ' + start_month.substring(0, 2);
    $('#start_month_'+elemID).val(start_month);
    
    //if (end_month != 'Ongoing')
    //{
    //    var e_month  = months[end_month.substring(2, 4).replace('0', '')-1];
    //    end_month   = e_month + ' ' + end_month.substring(0, 2);
    //}
    $('#end_month_'+elemID).val(end_month);
    
    calculateItemValue(elemID); // after loading the values calculate the item value
    
    if( product_status == 'Archive')
    {
        makeThisRowReadonly(elemID);
    }
}

function makeThisRowReadonly(elemID)
{
    $('#order_no_'+elemID).attr('disabled', 'disabled');
    $('#product_id_'+elemID).attr('disabled', 'disabled');
    $('#magazine_code_'+elemID).attr('disabled', 'disabled');
    $('#start_month_'+elemID).attr('disabled', 'disabled');
    $('#end_month_'+elemID).attr('disabled', 'disabled');
//    $('#page_'+elemID).attr('disabled', 'disabled');
    $('#qty_'+elemID).attr('disabled', 'disabled');
    $('#price_'+elemID).attr('disabled', 'disabled');
    $('#discount_'+elemID).attr('disabled', 'disabled');
    $('#total_'+elemID).attr('disabled', 'disabled');
    $('#alternative_'+elemID).attr('disabled', 'disabled');
    $('#productstatus_'+elemID).attr('disabled', 'disabled');
    
    // strink out the product description
    $('#proddesc_'+elemID).addClass('archive-strike-out');
    
    // add disable class to the fields
    $('#order_no_'+elemID).addClass('DISABLE');
    $('#product_id_'+elemID).addClass('DISABLE');
    $('#magazine_code_'+elemID).addClass('DISABLE');
    $('#start_month_'+elemID).addClass('DISABLE');
    $('#end_month_'+elemID).addClass('DISABLE');
//    $('#page_'+elemID).addClass('DISABLE');
    $('#qty_'+elemID).addClass('DISABLE');
    $('#price_'+elemID).addClass('DISABLE');
    $('#discount_'+elemID).addClass('DISABLE');
    $('#total_'+elemID).addClass('DISABLE');
    $('#alternative_'+elemID).addClass('DISABLE');
    $('#productstatus_'+elemID).addClass('DISABLE');
    
    // remove the text from td_action
    $('#td_action_'+elemID).first().remove();
}

/**
 * Get the new order ID by AJAX call
 * @returns {none}
 */
function getNewOrderNo()
{
    var customer_id = $('#customer_id').val();
    //alert('customer_id ::: ' + customer_id);
    $.ajax
    (
        {                                      
            url: 'order_manager.php?cmd=neworder&customer_id=' + customer_id,
            dataType: 'json',
            success: function(responseText)
            {
                if ( responseText != '')
                {
                    notsaved = true;
                    $('#order_no_' + parseInt(row_id-1)).val(responseText);
                    $('#order_details_id_' + parseInt(row_id-1)).val(responseText);
                    new_orders.push(responseText);
                }
                else
                {
                }
            }
        } 
    );
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
    var td_order_no     = '<td width="47" class="prepand3"><input type="text" name="order_no_'+row_id+'"        id="order_no_'+row_id+'"     value="" class="inputbox3" readonly style="width:36px;" /></td>';
    var td_product_id   = '<td width="63" class="prepand3"><input type="text" name="product_id_'+row_id+'"      id="product_id_'+row_id+'"   value="" class="inputbox3 W60" onClick="this.select(); showProductList('+row_id+');" onFocus="this.select(); showProductList('+row_id+');"><div id="selction"></div></td>';  
    var td_magazine     = '<td width="70" class="prepand3">'+ getMagazineList() +'</td>';
    var td_start_month  = '<td width="82" class="prepand3">'+ getMonthList('start_month') +'</td>';                                                                               // month column
    var td_end_month    = '<td width="82" class="prepand3">'+ getMonthList('end_month') +'</td>';                                                                               // month column
    var td_alternative  = '<td width="35" class="prepand3" align="center"><input type="checkbox" name="alternative_'+row_id+'" id="alternative_'+row_id+'"  value="Yes" class="checkboxS"></td>'; // qty column
//    var td_page         = '<td width="45" class="prepand3"><input type="text" name="page_'+row_id+'"            id="page_'+row_id+'"         value="" class="inputbox3 W28 TEXTRIGHT" /></td>'; // qty column
    var td_qty          = '<td width="38" class="prepand3"><input type="text" name="qty_'+row_id+'"             id="qty_'+row_id+'"          value="" class="inputbox3 W45 TEXTRIGHT" onChange="calculateItemValue('+row_id+');" maxlength="5" onkeypress="return isNumberKey(event);"></td>'; // qty column
    var td_unit_price   = '<td width="70" class="prepand3"><input type="text" name="price_'+row_id+'"           id="price_'+row_id+'"        value="" class="inputbox3 TEXTRIGHT" onChange="calculateItemValue('+row_id+');" onkeypress="return isNumberKey(event);"></td>';         // price column
    var td_discount     = '<td width="46" class="prepand3"><input type="text" name="discount_'+row_id+'"        id="discount_'+row_id+'"     value="" class="inputbox3 TEXTRIGHT" style="width:31px;" onChange="calculateDiscount('+row_id+');" onkeypress="return isNumberKey(event);"></td>'; // value column
    var td_total        = '<td width="75" class="prepand3"><input type="text" name="total_'+row_id+'"           id="total_'+row_id+'"        value="" class="inputbox3 W60 TEXTRIGHT" readonly></td>';         // total column
    var td_status       = '<td width="53" class="prepand3">'+ getStatusList() +'</td>';                                                        // status column
    //var td_placed_by    = '<td valign="top"><input type="text" name="placed_by_'+row_id+'"  id="placed_by_'+row_id+'"     value="'+placed_by+'" class="W120 H20 disabled" readonly></td>';         // total column
    //var td_order_date   = '<td valign="top"><input type="text" name="order_date_'+row_id+'" id="order_date_'+row_id+'"    value='+today+' class="W75 H20 disabled" readonly></td>';         // total column
    var td_action       = '<td width="50" class="prepand3" id="td_action_'+row_id+'"><a href="javascript: void(0);" onClick="deleteOrderDetailsRow('+row_id+');"><img src="/app_contents/common/images/cross2.png"></a> <a href="javascript:void(0);" onClick="copyRow('+row_id+');"><img src="/app_contents/common/images/copy.png"></a></td>';
    var hidden_field    = '<input type="hidden" id="pid_'+row_id+'" name="pid_'+row_id+'" value="" >\n\
                           <input type="hidden" id="order_details_id_'+row_id+'" name="order_details_id_'+row_id+'" value="">\n\
                           <input type="hidden" id="placed_by_id_'+row_id+'"     name="placed_by_id_'+row_id+'"     value="'+placed_by_id+'"> ';
     
    var td_blank     = '<td class="border">&nbsp;</td>';
    var td_proddesc  = '<td colspan="5" class="border"><div class="font"><label id="proddesc_'+row_id+'"></label></div></td>';
    
    $('<tr id="tr_'+row_id+'">'+ td_empty+td_order_no+td_product_id+td_magazine+td_start_month+td_end_month+td_alternative+td_qty+
                                 td_unit_price+td_discount+td_total+td_status+td_action+hidden_field+'</tr>' + 
                                 '<tr id="tr_'+row_id+'">'+td_blank+td_blank+td_proddesc+td_blank+td_blank+td_blank+td_blank+td_blank+td_blank+'</tr>').prependTo("#order_tbl > tbody")
    
    if (new_row)
    {    
        getNewOrderNo();             // genarate a new order number with AJAX call
    }
    
    $("#product_id_"+row_id).focus();
    getFullProductList(row_id);  // generate the full product list with AUTOCOMPLETE
    row_id++;    
    
}

function copyRow(srcID)
{
    addNewRow(1); // call add new row with a new order details ID
    elemID = parseInt(row_id-1);
    
    $('#product_id_'+elemID).val($('#product_id_'+srcID).val());
    $('#proddesc_'+elemID).val($('#proddesc_'+srcID).val());
    $('#magazine_code_'+elemID).val($('#magazine_'+srcID).val());
    $('#start_month_'+elemID).val($('#start_month_'+srcID).val());
    $('#end_month_'+elemID).val($('#end_month_'+srcID).val());
    //$('#page_'+elemID).val($('#page_'+srcID).val());
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
    var product_id       = $('#pid_'+elemID).val();
    
    if ( doConfirm("Order # " + order_details_id + " will be deleted.\n" + PROMPT_DELETE_CONFIRM ) )
    {
        $.ajax
        (
            {
                url: 'order_manager.php?cmd=deleteorder',                //the script to call to get data          
                data: "order_details_id="+order_details_id+"&customer_id="+customer_id+"&product_id="+product_id,
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

function mapProductDetails(itemID, elemID)
{
    //var item = itemID.split('##');
    
    var productID   = itemID;         //item[0];
    var product_option;
    
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
                    $('#proddesc_'+elemID).text(item_details[2]);
                    $('#qty_'+elemID).val(1);
                    $('#price_'+elemID).val(item_details[4]);
                    $('#magazine_code_'+elemID).val(item_details[5]);
                    $('#pid_'+elemID).val(productID);
                    $('#order_details_id_'+elemID).val($('#order_no_'+elemID).val());
                    
                    product_option = item_details[6];

                    calculateItemValue(elemID);
                    
                    if (product_option == 'Schedule')
                    {
                        addOrderScheduleDetails($('#customer_id').val(), $('#order_no_'+elemID).val(), productID);
                    }
                }
                else
                {
                }
            }
        } 
    );  
}

var schedule_ids;

function addOrderScheduleDetails(customer_id, order_details_id, product_id)
{
    $.ajax
    (
        {                                      
            url: 'order_manager.php?cmd=addschedule',
            data: "customer_id="+customer_id+"&order_details_id="+order_details_id+"&product_id="+product_id,
            dataType: 'json',
            success: function(responseText)
            {
                if ( responseText != '')
                {
                    if (schedule_ids)
                    {
                        schedule_ids = schedule_ids + ', ' + responseText;
                    }
                    else
                    {
                        schedule_ids = responseText;
                    }
                    
                    schedule_ids = schedule_ids;
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
function openProductBox(elemID, targetID)
{
    target_elemID = targetID;

    if (elemID == 'calander' && targetID.indexOf('end_') > -1)
    {
        $('#td_ongoing').show();
        $('#td_month').hide();
        $('#td_year').hide();
        $('#ongoing').attr("checked", true);
    }
    
    if (elemID == 'calander' && targetID.indexOf('start_') > -1)
    {
        $('#ongoing').attr("checked", false);
        $('#td_month').show();
        $('#td_year').show();
        $('#td_ongoing').hide();
    }
    
    $.fancybox.open
    (
        [
            {
                'href'   : '#'+elemID,
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

function saveOrderNote()
{
    var customer_id = $('#customer_id').val();
    var notes       = $('#instructions').val();
    
    $.ajax
    (
        {                                      
            url: 'order_manager.php?cmd=notes',
            data: "customer_id="+customer_id+'&order_notes='+notes,
            dataType: 'json',
            success: function(responseText)
            {
                if ( responseText != '')
                {
                    $('#order_notes').get(0).innerHTML = notes; 
                    $.fancybox.close();
                }
                else
                {
                }
            }
        } 
    );      
}

function isNumberKey(evt) 
{
   var charCode = (evt.which) ? evt.which : evt.keyCode;
   
   if(charCode == 46 || charCode == 8)
      return true;
      
   if (charCode < 48 || charCode > 57)
      return false;
   
   return true;
} 
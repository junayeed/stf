/*
 *   File: artwork_manager.js
 *
 */

RE_NAME     = new RegExp(/[^A-Z^a-z^ ^\.\^]$/);

var months            = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
var row_id            = 1;
var INNER_DELIM       = '$$$';
var OUTER_DELIM       = '###';
var SEPARATOR         = '_';
var DATE_SEPARATOR    = '-';
var VAT               = 0.20;
var target_elemID;

var schedule_date_error    = false;

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

    // if there is any error problem with the schedule 
    // do not submit the form
    if ( schedule_date_error )
    {
        alert('One of the schedule start and/or end month is not correct.\nPlease correct it.');
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
 * check whether the end month is greater than start month
 */

function validateScheduleDate(elemID)
{
    var schedule_start_month  = $('#start_month_' + elemID).val();
    var schedule_end_month    = $('#end_month_' + elemID).val();
    var order_no              = $('#order_no_' + elemID).val()
    
    resetColumn('start_month_'+elemID);
    resetColumn('end_month_'+elemID);
    
    if (schedule_end_month === 'Ongoing')
    {
        return;
    }
    else if(schedule_start_month > schedule_end_month)
    {
        alert("Order # " + order_no + "\nEnd Month should be greater than or equal to Start Month");
        highlightColumn('start_month_' + elemID);
        highlightColumn('end_month_' + elemID);
        schedule_date_error = true;       // flag the schedule_date_error
    }
    else if(schedule_end_month < schedule_start_month)
    {
        alert("Order # " + order_no + "\nEnd Month should be greater than or equal to Start Month");
        highlightColumn('start_month_' + elemID);
        highlightColumn('end_month_' + elemID);
        schedule_date_error = true;       // flag the schedule_date_error
    }
}

/**
 * Check schedule start and end month with the order strat and end month
 */
function checkScheduleDate(elemName, elemValue, elemID)
{
    schedule_date_error = false;
    
    validateScheduleDate(elemID);
    
    //alert('Name = ' + elemName + '\n Val = ' + elemValue + '\n ROW ID = ' + elemID);
    var order_start_month  = $('#order_start_month_'+elemID).val();
    var order_end_month    = $('#order_end_month_'+elemID).val();
    var order_no           = $('#order_no_'+elemID).val();
    
    if (elemName === "start_month")
    {
        resetColumn('start_month_'+elemID);
        
        if (elemValue < order_start_month)
        {
            alert('Order No: '+order_no+'\nSchedule start month must be equal or greater than order date');
            highlightColumn('start_month_'+elemID);
            schedule_date_error = true;       // flag the schedule_date_error
        }
    }
    
    if (elemName === "end_month")
    {
        resetColumn('end_month_'+elemID);
        //alert('Name = ' + elemName + '\n Val = ' + elemValue + '\n order end Month = ' + order_end_month);
        
        if (elemValue > order_end_month && order_end_month != 'Ongoing') 
        {
            alert('Order No: '+order_no+'\nSchedule end month must be equal or greater than order date');
            highlightColumn('end_month_'+elemID); 
            schedule_date_error = true;       // flag the schedule_date_error
        }
    }
}

var copyHistoryArray = {};

function findKey( value ) 
{
    for( var prop in copyHistoryArray ) 
    {
        if( copyHistoryArray[ prop ] === value )
        {
            return prop;
        }
    }
    
    return null;
}

function isContinuousScheduleDate(elemName, elemValue, elemID)
{
    //alert(copyHistoryArray);
    //alert('Array = ' + copyHistoryArray + '\nelem ID  = ' + elemID +'\nkey = '+ findKey(elemID));  // ajaj
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
    
    var selectObj = '<select name="'+elemName+'_'+row_id+'" id="'+elemName+'_'+row_id+'" class="combo1 W73" \n\
                             onChange="checkScheduleDate(\''+elemName+'\', this.value, \''+row_id+'\'); \n\
                                       isContinuousScheduleDate(\''+elemName+'\', this.value, \''+row_id+'\');">';
    
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
    
    $.fancybox.close(); 
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

function convertNumber2Date(num)
{
    if (num != 'Ongoing')
    {
        monthIndex = num.substring(2, 4);
        if (monthIndex != '10')
        {
            month  = months[num.substring(2, 4).replace('0', '')-1];
        }
        else 
        {
            month  = months[num.substring(2, 4)-1];
        }
        
        return month + ' ' + num.substring(0, 2);
    }
    else
    {
        return num;        
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

function populateOrderDetails(product_details_id, product_id, magazine_code, start_month, end_month, position, product_code, 
                              qty_per_unit, schedule_id, file_name, date_sensitive, order_start_month, order_end_month, product_status)
{
    var elemID = row_id-1; 
    
    if( !start_month ) start_month  = order_start_month;
    if( !end_month )   end_month    = order_end_month;
    
    $('#order_no_'+elemID).val(product_details_id);
    $('#product_id_'+elemID).val(product_code);
    $('#size_'+elemID).val( (qty_per_unit*1).toFixed(3) );
    $('#magazine_code_'+elemID).val(magazine_code);
    $('#order_start_'+elemID).val( convertNumber2Date(order_start_month) + '-' + convertNumber2Date(order_end_month) );
//    $('#order_end_'+elemID).val( convertNumber2Date(order_end_month) );
    $('#start_month_'+elemID).val(start_month);
    $('#end_month_'+elemID).val(end_month);
    $('#pid_'+elemID).val(product_id);
    $('#order_details_id_'+elemID).val(product_details_id);
    $('#schedule_id_'+elemID).val(schedule_id);
    $('#file_name_'+elemID).val(file_name);
    $('#position_'+elemID).val(position);
    $('#order_start_month_'+elemID).val(order_start_month);
    $('#order_end_month_'+elemID).val(order_end_month);
    $('#instruction_'+elemID).val($('#instruction_text_'+schedule_id).text());
    
    if (date_sensitive == 'Yes') 
    {
        $('#date_sensitive_'+elemID).prop('checked', true);
        $('#date_sensitive_'+elemID).val('Yes');
    }
    $('#magazine_code_'+elemID).hide();
    
    $('#magazine_'+elemID).val($('#magazine_code_'+elemID+' option:selected').text());

    calculateItemValue(elemID); // after loading the values calculate the item value
 
    if( product_status == 'Archive')
    {
        makeThisRowReadonly(elemID);
    }
}

function makeThisRowReadonly(elemID)
{
    $('#start_month_'+elemID).attr('disabled', 'disabled');
    $('#end_month_'+elemID).attr('disabled', 'disabled');
    $('#position_'+elemID).attr('disabled', 'disabled');
    $('#date_sensitive_'+elemID).attr('disabled', 'disabled');
    $('#file_name_'+elemID).attr('disabled', 'disabled');
    $('#instruction_'+elemID).attr('disabled', 'disabled');
    
    // add disable class to the fields
    $('#shape_'+elemID).addClass('DISABLE');
    $('#size_shape_'+elemID).addClass('DISABLE');
    $('#position_'+elemID).addClass('DISABLE');
    $('#file_name_'+elemID).addClass('DISABLE');
    
    // remove the text from td_action
    $('#td_action_'+elemID).first().remove();
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

function toggleCheckBoxValue(elemID)
{
    if ( $('#'+elemID).is(':checked') )
    {
        $('#'+elemID).val('Yes');
    }
    else
    {
        $('#'+elemID).val('');
    }
}

/**
 * Add a new row after click on "Add a new row".
 * @returns none
 */

function addNewRow(new_row, srcID)
{
    var td_empty              = '<td class="prepand3">&nbsp;</td>';
    var td_order_no           = '<td class="prepand3"><input type="text" name="order_no_'+row_id+'" id="order_no_'+row_id+'" value="" class="inputbox3 noborder W28" readonly></td>';
    var td_size               = '<td class="prepand3"><input type="text" name="size_'+row_id+'" id="size_'+row_id+'" value="" class="inputbox3 noborder W38" readonly></td>';  
    var td_magazine           = '<td class="prepand3">'+ getMagazineList() +'<input type="text" name="magazine_'+row_id+'" id="magazine_'+row_id+'" value="" class="inputbox3 noborder W28" readonly></td>';
    //var td_start_month      = '<td class="prepand3"><input type="text" name="start_month_'+row_id+'" id="start_month_'+row_id+'" value="" class="inputbox3 noborder" readonly></td>';                                                                               // month column
    //var td_end_month        = '<td class="prepand3"><input type="text" name="end_month_'+row_id+'" id="end_month_'+row_id+'" value="" class="inputbox3 noborder" readonly></td>';                                                                               // month column
    var td_order_start        = '<td class="prepand3"><input type="text" name="order_start_'+row_id+'" id="order_start_'+row_id+'" value="" class="inputbox3 noborder W90" readonly></td>';                                                                               // month column
//    var td_order_end          = '<td class="prepand3"><input type="text" name="order_end_'+row_id+'" id="order_end_'+row_id+'" value="" class="inputbox3 noborder" readonly></td>';                                                                               // month column
    var td_start_month        = '<td class="prepand3">'+ getMonthList('start_month') +'</td>';                                                                               // month column
    var td_end_month          = '<td class="prepand3">'+ getMonthList('end_month') +'</td>';                                                                               // month column
    //var td_shape            = '<td class="prepand3">'+ getShapeList() +'</td>'; // qty column
    var td_position           = '<td class="prepand3"><input type="text" name="position_'+row_id+'" id="position_'+row_id+'" value="" class="inputbox3 W75"></td>'; // qty column
    var td_date_sensitive     = '<td class="prepand3" align="center"><input name="date_sensitive_'+row_id+'" id="date_sensitive_'+row_id+'" type="checkbox" value="" onChange="toggleCheckBoxValue(this.id);" /></td>'; // qty column
    var td_file_name          = '<td class="prepand3"><input type="text" name="file_name_'+row_id+'" id="file_name_'+row_id+'" value="" class="inputbox3 W185" maxlength="30"></td>';
    var td_action             = '<td class="prepand3" id="td_action_'+row_id+'"><a href="javascript: void(0);" onClick="deleteOrderDetailsRow('+row_id+');"><img src="/app_contents/common/images/cross2.png"></a> <a href="javascript:void(0);" onClick="copyRow('+row_id+');"><img src="/app_contents/common/images/copy.png"></a></td>';
    var hidden_field          = '<input type="hidden" id="pid_'+row_id+'"              name="pid_'+row_id+'"              value="" >\n\
                                 <input type="hidden" id="schedule_id_'+row_id+'"      name="schedule_id_'+row_id+'"      value="" >\n\
                                 <input type="hidden" id="order_details_id_'+row_id+'" name="order_details_id_'+row_id+'" value="">\n\
                                 <input type="hidden" id="order_start_month_'+row_id+'" name="order_start_month_'+row_id+'" value="">\n\
                                 <input type="hidden" id="order_end_month_'+row_id+'"   name="order_end_month_'+row_id+'"   value="">';
    var td_instruction  = '<td valign="top" colspan="11">\n\
                               <div class="prepand2 border padding2" align="right">\n\
                                  <textarea name="instruction_'+row_id+'" id="instruction_'+row_id+'" cols="" rows="" class="textarea" placeholder="Type Instruction Here"></textarea>\n\
                               </div>\n\
                           </td>';     
    
    if (new_row)
    {
        $('<tr id="tr_'+row_id+'">'+td_empty+td_order_no+td_magazine+td_order_start+td_start_month+td_end_month+td_size+td_position+
                                    td_date_sensitive+td_file_name+td_action+hidden_field+'</tr>').insertAfter("#tr_instruction_"+srcID);
    
        $('<tr id="tr_instruction_'+row_id+'">'+td_empty+td_instruction+'</tr>').insertAfter("#tr_"+row_id);
    }
    else
    {    
        $('<tr id="tr_'+row_id+'">'+td_empty+td_order_no+td_magazine+td_order_start+td_start_month+td_end_month+td_size+td_position+
                                    td_date_sensitive+td_file_name+td_action+hidden_field+'</tr>').appendTo("#order_tbl > tbody");
    
        $('<tr id="tr_instruction_'+row_id+'">'+td_empty+td_instruction+'</tr>').appendTo("#order_tbl > tbody");
        
    }   
    
    $('#magazine_code_'+row_id).hide();
    
    row_id++;
}

Array.prototype.insert = function (index, item) {
  this.splice(index, 0, item);
};

function copyRow(srcID)
{
    addNewRow(1, srcID); // call add new row with a new order details ID
    elemID = parseInt(row_id-1);
    //copyHistoryArray.insert(srcID, elemID); // copy the newly created ID to its source ID   ajaj
    copyHistoryArray.srcID = elemID;
    
    $('#order_no_'+elemID).val($('#order_no_'+srcID).val());
    $('#size_'+elemID).val($('#size_'+srcID).val());
    $('#magazine_'+elemID).val($('#magazine_'+srcID).val());
    $('#start_month_'+elemID).val($('#start_month_'+srcID).val());
    $('#end_month_'+elemID).val($('#end_month_'+srcID).val());
    $('#position_'+elemID).val($('#position_'+srcID).val());
    $('#file_name_'+elemID).val($('#file_name_'+srcID).val());
    $('#pid_'+elemID).val($('#pid_'+srcID).val());
    $('#order_details_id_'+elemID).val($('#order_details_id_'+srcID).val());
    $('#order_start_month_'+elemID).val($('#order_start_month_'+srcID).val());
    $('#order_end_month_'+elemID).val($('#order_end_month_'+srcID).val());
    $('#instruction_'+elemID).val($('#instruction_'+srcID).val());
    $('#order_start_'+elemID).val( $('#order_start_'+srcID).val() );
    $('#order_end_'+elemID).val( $('#order_end_'+srcID).val() );

    if ($('#date_sensitive_'+srcID).is(':checked'))
    {
        $('#date_sensitive_'+elemID).attr("checked", true);
    }
}

/**
 * Delete a specific row from DB by AJAX call and remove the row from the table
 * @param {int} elemID
 * @returns none
 */

function deleteOrderDetailsRow(elemID)
{
    var schedule_id       = $('#schedule_id_'+elemID).val();
    var order_details_id  = $('#order_details_id_'+elemID).val();
    
    //if ( doConfirm(" Order # " + order_details_id + " will be deleted.\n" + PROMPT_DELETE_CONFIRM ) )
    if ( doConfirm("This schedule entry will be deleted\n" + PROMPT_DELETE_CONFIRM ) )
    {
        $.ajax
        (
            {
                url: 'schedule_manager.php?cmd=deleteschedule',                //the script to call to get data          
                data: "schedule_id="+schedule_id,
                dataType: 'json',                                        //data format      
                complete: function(responseText)                          //on recieve of reply
                {
                    $("#order_tbl > tbody").find("#tr_" + elemID).fadeOut(1000,function() 
                    {
                        $('#order_tbl > tbody > #tr_' + elemID).remove();
                        $('#order_tbl > tbody > #tr_instruction_' + elemID).remove();
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
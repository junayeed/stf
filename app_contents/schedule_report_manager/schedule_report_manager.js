/*
 *   File: schedule_report_manager.js
 *
 */

var itemStatusArray  = new Array('B', 'C', 'P');
var months           = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];

function monthReturn(y,m) 
{
    var val = months[m-1]+" "+y.toString().substring(2);
    
    $('#'+targetID).val(val);
}

function validateFields(frm)
{
   with(frm)
   {
   }

    return true;
}

function doFormSubmit()
{
   var frm = document.reportForm;
   
   with(frm)
   {
       submit();
   }
}

/**
 * Generate the status dropdown list 
 * @returns {String}
 */
function getStatusList(elemVal)
{
    $('#status').append($('<option>') );
    for (var i = 0; i < itemStatusArray.length; i++)
    {
        $('#status').append($('<option>', { value: itemStatusArray[i], text: itemStatusArray[i] } ) );
    }
    
    $('#status').val(elemVal);
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

function getMonthList(elemVal, elemName)
{
    var dt          = new Date();
    var start_year  = 2013;
    var end_year    = start_year+2;
    
    $('#'+elemName).append($('<option>') );
    
    if (elemName == 'end_month')
    {
        $('#'+elemName).append($('<option>', { value: "Ongoing", text : "Ongoing" } ) );
    }
    
    for(j=start_year; j<=end_year; j++)
    {    
        for (var i = 0; i < months.length ; i++)
        {
            yr2 = j.toString().substring(2);
            $('#'+elemName).append($('<option>', { value: yr2+padNumber(i%12+1, 2), text: months[i%12]+' '+yr2 } ) );
        }
    }
    
    $('#'+elemName).val(elemVal);
}

function doClearForm()
{
    $('#magazine').val('');
    $('#start_month').val('');
    $('#end_month').val('');
    $('#status').val('');
    
    location.href = 'http://'+document.domain+'/app/schedule_report_manager/schedule_report_manager.php';
}

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
    //var hidden_field = target_elemID.replace('show_', '');
    
    if($('#ongoing').attr("checked") != 'checked')
    {
        $('#'+target_elemID).val(m_txt + ' ' + y_txt );
        //$('#'+hidden_field).val(y_txt + m_val );
    }
    else
    {
        $('#'+target_elemID).val('Ongoing');
        //$('#'+hidden_field).val('Ongoing');
    }
    
    $.fancybox.close(); // ajaj
}
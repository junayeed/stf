/*jslint  browser: true, white: true, plusplus: true */
/*global $: true */

function getCustomerList()
{
    // Load countries then initialize plugin:
    $.ajax({
        url: 'http://'+document.domain+'/app/order_manager/order_manager.php?cmd=getCustomerAutoComplete',
        cache: false,
        dataType: 'json'
    }).done(function (source) {

        var countriesArray = $.map(source, function (value, key) { return { value: value, data: key }; }),
            countries = $.map(source, function (value) { return value; });
       
        // Initialize ajax autocomplete:
        $('#autocomplete-ajax').autocomplete({
            serviceUrl: '/autosuggest/service/url',
            onSelect: function(suggestion) {
                alert(suggestion.id)
                //$('#selction-ajax').html('You selected: ' + suggestion.value + ', ' + suggestion.data);
            }
        });

        // Initialize autocomplete with local lookup for report module.
        // Do not why but I've to copy/paste same thing here but with different name :('
        $('#autocomplete-report').autocomplete({
            lookup: countriesArray,
            onSelect: function (suggestion) {
                $('#selection').html('You selected: ' + suggestion.value + ', ' + suggestion.data);
                //alert(suggestion.data)
                //location.href = 'http://'+document.domain+'/app/order_manager/order_manager.php?customer_id='+suggestion.data+'&cmd=edit';
                // copy the customer id in a hidden field
                $('#customer_id').val(suggestion.data);
            }
        });
        
        // Initialize autocomplete with local lookup:
        $('#autocomplete').autocomplete({
            lookup: countriesArray,
            onSelect: function (suggestion) {
                $('#selection').html('You selected: ' + suggestion.value + ', ' + suggestion.data);
                //alert(suggestion.data)
                location.href = 'http://'+document.domain+'/app/order_manager/order_manager.php?customer_id='+suggestion.data+'&cmd=edit';
                // copy the customer id in a hidden field
                $('#customer_id').val(suggestion.data);
            }
        });
        
        // Initialize autocomplete with custom appendTo:
        $('#autocomplete-custom-append').autocomplete({
            lookup: countriesArray,
            appendTo: '#suggestions-container'
        });

        // Initialize autocomplete with custom appendTo:
        $('#autocomplete-dynamic').autocomplete({
            lookup: countriesArray
        });
        
    });
}

var productArray;
var products;

function getFullProductList(elemID)
{
    // Load countries then initialize plugin:
    $.ajax
    (
        {
            url: 'order_manager.php?cmd=productlist',
            cache: false,
            dataType: 'json'
        }
    ).done(function (source) 
    {
        productArray = $.map(source, function (value, key) { return { value: value, data: key }; }),
        products     = $.map(source, function (value) { return value; });

        // Initialize autocomplete with local lookup:
        $('#product_id_'+elemID).autocomplete
        (
            {
                lookup: productArray,
                onSelect: function (suggestion) 
                {
                    //alert(suggestion.data);
                    mapProductDetails(suggestion.data, elemID);
                    // copy the customer id in a hidden field
                }
            }
        );
        
    });
}

function showProductList(elemID)
{
    $('#order_no_'+elemID).click
    ( 
        { 
            lookup: productArray,
            onSelect: function (suggestion) 
            {
                //alert(suggestion.data);
                mapProductDetails(suggestion.data, elemID);
                // copy the customer id in a hidden field
            }
        }
    );
}

function showAutoCompleteHelpText()
{
    $('#autocomplete-box').show();
}

function hideAutoCompleteHelpText()
{
    $('#autocomplete-box').hide();
}
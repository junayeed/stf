/*
 *   File: product_group_manager.js
 *
 */

RE_NAME     = new RegExp(/[^A-Z^a-z^ ^\.\^]$/);
RE_EMAIL    = new RegExp(/^[A-Za-z0-9](([_|\.|\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([_|\.|\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]{2,})$/);
RE_USERNAME = new RegExp(/^[a-z0-9\_]+$/);
RE_DECIMAL  = new RegExp(/^[0-9]{1,8}([\.]{1}[0-9]{1,2})?$/);
RE_NUMBER   = new RegExp(/^[0-9]+$/);
RE_PHONE    = new RegExp(/^((\d\d\d)|(\(\d\d\d\)))?\s*[\.-]?\s*(\d\d\d)\s*[\.-]?\s*(\d\d\d\d)$/);
RE_ZIP      = new RegExp(/^[0-9]{5}(([\-\ ])?[0-9]{4})?$/);

function setupForm(frm)
{
   with (frm)
   {
      setRequiredField(product_group,    'textbox',   'product_group');
      setRequiredField(pg_code,          'textbox',   'pg_code');
   }
}

function validateFields(frm)
{
    with(frm)
    {
        if (!RE_NUMBER.exec(delivery_date.value))
        {
            highlightTableColumn('delivery_date');
            alert('Please enter a number');
            return false;
        }

        return true;
    }
}

function doFormSubmit()
{
    requiredFields.length = 0;

    var errCnt = 0;
    var frm = document.productGroupForm;

    // Setup required fields
    setupForm(frm);

    // Validate form for required fields
    errCnt = validateForm(frm);

    if (errCnt)
    {
        alert(MISSING_REQUIRED_FIELDS);
        return false;
    }

    return true;
}
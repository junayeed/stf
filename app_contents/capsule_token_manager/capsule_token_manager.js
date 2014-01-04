/*
 *   File: capsule_token_manager.js
 *
 */

function setupForm(frm)
{
    with (frm)
    {
        setRequiredField(capsule_token,    'textbox',   'capsule_token');
        setRequiredField(app_name,         'textbox',   'app_name');
    }
}

function doFormSubmit()
{
    requiredFields.length = 0;

    var errCnt = 0;
    var frm = document.capsuleTokenForm;

    // Setup required fields
    setupForm(frm);

    // Validate form for required fields
    errCnt = validateForm(frm);

    if (errCnt)
    {
        alert(MISSING_REQUIRED_FIELDS);
        return false;
    }

    else
    {
        return true;
    }
}

function doClearForm()
{
    $('#capsule_token').val('');
    $('#app_name').val('');
}
<?php /* Smarty version 2.6.6, created on 2014-01-04 07:47:52
         compiled from E:/xampp/htdocs/stf/app_contents/standard/registration/registration.html */ ?>
<html>
    <head>
        <title>User Registration</title><?php if ($this->_tpl_vars['SYSTEM_PRODUCTION_MODE'] != 'Yes'): ?><!-- ** THIS NOTE ONLY SHOWS IN DEVELOPMENT MODE *** Purpose: This template is used to show the login form Javascript requirements: In addition to standard javascripts such as evoknow.js, messages.js you will need to create <?php echo $this->_tpl_vars['SYSTEM_COMMON_JAVASCRIPT_DIR']; ?>
/<?php echo $this->_tpl_vars['SYSTEM_APP_PREFIX']; ?>
/<?php echo $this->_tpl_vars['SYSTEM_APP_PREFIX']; ?>
.js to store any custom javascript functions needed for this form.--><?php endif; ?>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <!-- load javascript error/success messages for javascripts -->
        <script language="JavaScript" src="<?php echo $this->_tpl_vars['SYSTEM_COMMON_JAVASCRIPT_DIR']; ?>
/messages.js"></script>
        <!-- load standard javascript library -->
        <script language="JavaScript" src="<?php echo $this->_tpl_vars['SYSTEM_COMMON_JAVASCRIPT_DIR']; ?>
/common.js"></script>
        <!-- load current application specific javascript -->
        <script language="JavaScript" src="<?php echo $this->_tpl_vars['REL_TEMPLATE_DIR']; ?>
/<?php echo $this->_tpl_vars['SYSTEM_APP_PREFIX']; ?>
.js"></script>
        <script language="JavaScript" src="<?php echo $this->_tpl_vars['SYSTEM_COMMON_JAVASCRIPT_DIR']; ?>
/jquery.js"></script>
        <!-- setup default info -->
        <link rel="stylesheet" href="/app_contents/common/css/login.css" type="text/css">
    </head>
    <body>
        <div class="registration">
            <h1>Sweden Bangladesh Trust Fund Registration Form</h1>
            <form name="registrationForm" onSubmit="return dologinFormSubmit();" action="<?php echo $this->_tpl_vars['SCRIPT_NAME']; ?>
" method="POST">
                <div id="rigistrationContent">
                    <table width="100%">
                        <tr>
                            <td>First Name</td>
                            <td><input type="text" name="first_name" id="first_name" value="" class="W278"></td>
                        </tr>
                        <tr>
                            <td>Last Name</td>
                            <td><input type="text" name="last_name" id="last_name" value="" class="W278"></td>
                        </tr>
                        <td>Gender</td>
                        <td>
                            <label class="label_radio" for="radio-01"><input name="gender" id="radio-01" value="Male" type="radio" />Male</label>
                            <label class="label_radio" for="radio-02"><input name="gender" id="radio-02" value="Female" type="radio" />Female</label> 	
                        </td>
                        <tr>
                            <td>Email</td>
                            <td><input type="text" name="email" id="email" value="" class="W278" onchange="isEmailExists();"></td>
                        </tr>
                        <tr>
                            <td>Username</td>
                            <td><input type="text" name="username" id="username" value="" class="W278" onchange="isUserNameExists();"></td>
                        </tr>
                        <tr>
                            <td>Password</td>
                            <td><input type="password" name="password" id="password" value="" class="W278" onchange="isPSWMatched();"></td>
                        </tr>
                        <tr>
                            <td>Confirm Password</td>
                            <td><input type="password" name="confirm_password" id="confirm_password" value="" class="W278" onchange="isPSWMatched();"></td>
                        </tr>
                    </table>
                    <p class="submit">
                        <input type="submit" name="commit" value="Save">
                        <input type="reset" name="reset" value="Clear">
                    </p>
                </div>
            </form>
        </div>
    </body>
</html>
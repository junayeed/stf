<?php /* Smarty version 2.6.6, created on 2014-01-04 10:03:21
         compiled from E:/xampp/htdocs/stf/app_contents/standard/login/login.html */ ?>
<html>
    <head>
        <title>User Login</title><?php if ($this->_tpl_vars['SYSTEM_PRODUCTION_MODE'] != 'Yes'): ?><!-- ** THIS NOTE ONLY SHOWS IN DEVELOPMENT MODE *** Purpose: This template is used to show the login form Javascript requirements: In addition to standard javascripts such as evoknow.js, messages.js you will need to create <?php echo $this->_tpl_vars['SYSTEM_COMMON_JAVASCRIPT_DIR']; ?>
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
        <!-- setup default info -->
        <link rel="stylesheet" href="/app_contents/common/css/login.css" type="text/css">
    </head>
    <body>
        <div class="login">
            <h1>Login to Web App</h1>
            <form name="loginForm" onSubmit="return dologinFormSubmit();" action="<?php echo $this->_tpl_vars['SCRIPT_NAME']; ?>
" method="POST">
                <p><input type="text" name="loginid" id="loginid" value="" placeholder="Username or Email"></p>
                <p><input type="password" name="password" id="password" value="" placeholder="Password"></p>
                <p class="submit"><input type="submit" name="commit" value="Login"></p>
            </form>
        </div>

        <div class="login-help">
            <p>Not registered yet? <a href="/app/standard/registration/registration.php">Click here to register.</a>.</p>
        </div>
    </body>
</html>
<?php /* Smarty version 2.6.6, created on 2014-01-05 06:59:28
         compiled from E:/xampp/htdocs/stf/app_contents/standard/registration/landingpage.html */ ?>
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
            <form name="registrationForm"  action="<?php echo $this->_tpl_vars['SCRIPT_NAME']; ?>
" method="POST">
                <div id="rigistrationContent">
                    <?php if ($this->_tpl_vars['msg'] == 'Success'): ?>
                      <div class="success">
                        <h3> Congratulations! </h3>
                        <p>
                            You have successfully registered for Sweden Bangladesh Trust Fund. Please login and submit your application.
                        </p>
                      </div>  
                        <p class="submit">
                            <input type="button" name="commit" value="Go to Login" onclick="location.href='/app/standard/login/login.php'" >
                        </p>
                        
                    <?php else: ?>
                       <div class="falure">
                        <h3> Sorry! </h3>
                        <p>
                            Registration not Completed. Please Try again...
                        </p>
                        
                       </div> 
                       <p class="submit">
                        <input type="button" name="commit" value="Go to Registration" onclick="location.href='/app/standard/registration/registration.php'" >
                       </p>
                    <?php endif; ?>
                    
                </div>
              </form>
        </div>
    </body>
</html>
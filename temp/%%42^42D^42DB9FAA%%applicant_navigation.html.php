<?php /* Smarty version 2.6.6, created on 2014-01-05 06:40:28
         compiled from E:/xampp/htdocs/stf/app_contents/standard/user_home/applicant_navigation.html */ ?>
<script type="text/javascript" src="/ext/scripts/jquery-1.8.2.min.js"></script>
<script type="text/javascript" src="/ext/src/jquery.autocomplete.js"></script>
<script type="text/javascript" src="/ext/scripts/demo.js"></script>

<body>
    <div id="top-full">  <!-- top-full starts here -->
        <div class="wrap"> <!-- wrap starts here-->
            <div class="top">
                <div class="home">
                    <a href="/app/user_manager/user_manager.php"><img src="/app_contents/common/images/users.png" alt="Customers" /></a>
                    <h5>Profile</h5>
                </div>
                <div class="home">
                    <a href="/app/application_manager/application_manager.php?cmd=edit"><img src="/app_contents/common/images/folder_apps_copy.png" alt="Home" /></a>
                    <h5>Application</h5>
                </div>
                <div class="top-search">

                </div>
                <div class="logout">
                    <p><?php echo $this->_tpl_vars['USER_FIRST']; ?>
 <?php echo $this->_tpl_vars['USER_LAST']; ?>
</p>
                    <p><a href="/app/standard/logout/logout.php">Logout</a></p>
                </div>
<!--                <div class="admin-image">
                    <img src="<?php echo $this->_tpl_vars['USER_IMAGE']; ?>
?<?php echo time(); ?>
" alt="Image" width="40" height="40"/>
                    
                </div>-->
             </div>
        </div> <!-- wrap ends here-->
    </div> <!-- top-full ends here-->
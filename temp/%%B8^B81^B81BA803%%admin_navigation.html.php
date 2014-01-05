<?php /* Smarty version 2.6.6, created on 2014-01-05 11:52:07
         compiled from E:/xampp/htdocs/stf/app_contents/standard/user_home/admin_navigation.html */ ?>
<script type="text/javascript" src="/ext/scripts/jquery-1.8.2.min.js"></script>
<script type="text/javascript" src="/ext/src/jquery.autocomplete.js"></script>
<script type="text/javascript" src="/ext/scripts/demo.js"></script>

<body>
<div id="top-full">  <!-- top-full starts here -->
    <div class="wrap"> <!-- wrap starts here-->
        <div class="top">
    	<div class="home">
            <a href="/app/standard/user_home/user_home.php"><img src="/app_contents/common/images/home.png" alt="Home" width="27" /></a>
            <h5>Home</h5>
        </div>
        <div class="home">
            <a href="/app/customer_manager/customer_manager.php"><img src="/app_contents/common/images/users.png" alt="Applicants" /></a>
            <h5>Applicants</h5>
        </div>
        <div class="home">
            <a href="/app/report_manager/report_manager.php"><img src="/app_contents/common/images/reports.png" alt="Reports" /></a>
            <h5>Report</h5>
        </div>
        <div class="home">
            <a href="/app/user_manager/user_manager.php"><img src="/app_contents/common/images/settings.png" alt="Setting" /></a>
            <h5>Settings</h5>
        </div>
        <div class="setting">
            <p><?php echo $this->_tpl_vars['USER_FIRST']; ?>
 <?php echo $this->_tpl_vars['USER_LAST']; ?>
 (<?php echo $this->_tpl_vars['USER_TYPE']; ?>
)</p>
            <p><a href="/app/standard/logout/logout.php">Logout</a></p>
        </div>
     </div>

</div> <!-- wrap ends here-->
</div> <!-- top-full ends here-->
<?php /* Smarty version 2.6.6, created on 2013-12-14 01:27:42
         compiled from E:/xampp/htdocs/stf/app_contents/standard/user_home/admin_navigation.html */ ?>
<script type="text/javascript" src="/ext/scripts/jquery-1.8.2.min.js"></script>
<script type="text/javascript" src="/ext/src/jquery.autocomplete.js"></script>
<script type="text/javascript" src="/ext/scripts/demo.js"></script>

<body>
<div id="top-full">  <!-- top-full starts here -->
    <div class="wrap"> <!-- wrap starts here-->
        <div class="top">
    	<div class="home">
            <a href="/app/standard/user_home/user_home.php"><img src="/app_contents/common/images/home.png" alt="Home" /></a>
        </div>
        <div class="home">
            <a href="/app/customer_manager/customer_manager.php"><img src="/app_contents/common/images/customers.png" alt="Customers" /></a>
        </div>
        <div class="home">
            <a href="/app/report_manager/report_manager.php"><img src="/app_contents/common/images/reports.png" alt="Reports" /></a>
        </div>
        <div class="top-search">
            <table width="416" cellpadding="0" cellspacing="0" border="0">
            	<tr>
                    <td width="400">
                        <input type="text" name="customer_name" id="autocomplete" value="<?php echo $this->_tpl_vars['customer_name']; ?>
" class="inputbox" placeholder="Search Customers" onclick="showAutoCompleteHelpText();" onfocus="showAutoCompleteHelpText();" onblur="hideAutoCompleteHelpText();" >
                        <div id="selction-ajax"></div>
                        <div id="autocomplete-box" style="display: none; ">Add or edit an order by typing any part of the Organisation name.</div>
                    </td>
                    <td width="16"><input name="" type="image" src="/app_contents/common/images/search.png" /></td>
                </tr>
            </table>
        </div>
        <div class="logout">
            <p><?php echo $this->_tpl_vars['USER_FIRST']; ?>
 <?php echo $this->_tpl_vars['USER_LAST']; ?>
 (<?php echo $this->_tpl_vars['USER_TYPE']; ?>
)</p>
            <p><a href="/app/standard/logout/logout.php">Logout</a></p>
        </div>
        <div class="admin-image">
            <img src="<?php echo $this->_tpl_vars['USER_IMAGE']; ?>
?<?php echo time(); ?>
" alt="Image" width="40" height="40"/>
        </div>
        <div class="setting">
            <a href="/app/user_manager/user_manager.php"><img src="/app_contents/common/images/settings.png" alt="Setting" /></a>
        </div>
     </div>

</div> <!-- wrap ends here-->
</div> <!-- top-full ends here-->
<script>
     getCustomerList();
</script>
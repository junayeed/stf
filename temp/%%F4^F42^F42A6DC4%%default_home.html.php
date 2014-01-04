<?php /* Smarty version 2.6.6, created on 2014-01-04 08:49:32
         compiled from E:/xampp/htdocs/stf/app_contents/standard/user_home/default_home.html */ ?>
<html>

     <head>
        <title><?php echo $this->_tpl_vars['USER_FIRST']; ?>
:</title>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
        <!-- stylesheets -->
        <link rel="stylesheet" type="text/css" href="/app_contents/local/theme/admin/css/reset.css" />
        <link rel="stylesheet" type="text/css" href="/app_contents/local/theme/admin/css/style.css" media="screen" />
        <link id="color" rel="stylesheet" type="text/css" href="/app_contents/local/theme/admin/css/colors/blue.css" />

        <!-- scripts (jquery) -->
        <script src="/app_contents/local/theme/admin/scripts/jquery-1.4.2.min.js" type="text/javascript"></script>
        <!--[if IE]><script language="javascript" type="text/javascript" src="/app_contents/local/theme/admin/scripts/excanvas.min.js"></script><![endif]-->
        <script src="/app_contents/local/theme/admin/scripts/jquery-ui-1.8.custom.min.js" type="text/javascript"></script>
        <script src="/app_contents/local/theme/admin/scripts/jquery.ui.selectmenu.js" type="text/javascript"></script>
        
        <script src="/app_contents/local/theme/admin/scripts/tiny_mce/tiny_mce.js" type="text/javascript"></script>
        <script src="/app_contents/local/theme/admin/scripts/tiny_mce/jquery.tinymce.js" type="text/javascript"></script>

        <!-- scripts (custom) -->
        <script src="/app_contents/local/theme/admin/scripts/smooth.js" type="text/javascript"></script>
        <script src="/app_contents/local/theme/admin/scripts/smooth.menu.js" type="text/javascript"></script>
        
        <script src="/app_contents/local/theme/admin/scripts/smooth.table.js" type="text/javascript"></script>
        <script src="/app_contents/local/theme/admin/scripts/smooth.form.js" type="text/javascript"></script>
        <script src="/app_contents/local/theme/admin/scripts/smooth.dialog.js" type="text/javascript"></script>

        <script src="/app_contents/local/theme/admin/scripts/smooth.autocomplete.js" type="text/javascript"></script>
        <script language="JavaScript" src="<?php echo $this->_tpl_vars['SYSTEM_COMMON_JAVASCRIPT_DIR']; ?>
/messages.js"></script>
				<script language="JavaScript" src="<?php echo $this->_tpl_vars['SYSTEM_COMMON_JAVASCRIPT_DIR']; ?>
/common.js"></script>
				<script language="JavaScript" src="<?php echo $this->_tpl_vars['SYSTEM_COMMON_JAVASCRIPT_DIR']; ?>
/sorttable.js"></script>
				<script language="JavaScript" src="<?php echo $this->_tpl_vars['SYSTEM_COMMON_JAVASCRIPT_DIR']; ?>
/CalendarPopup.js"></script>
				<script language="JavaScript" src="<?php echo $this->_tpl_vars['REL_TEMPLATE_DIR']; ?>
/<?php echo $this->_tpl_vars['SYSTEM_APP_PREFIX']; ?>
.js"></script>
        
    </head>
       
        
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['USER_HEADER'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        
         <div id="content">
         	
         	   <div id="left"> 
         	      <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['USER_NAVIGATION'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
         	   </div>
         	   <div id="right">
         	      <?php echo $this->_tpl_vars['contents']; ?>

         	   </div>   
         	   <!-- footer -->
         </div>
         
        <div id="footer">
            <p>Copyright &copy; 2011 ASICT. All Rights Reserved.</p>
        </div>
        <!-- end footert -->
<body>
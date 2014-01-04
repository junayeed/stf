<?php /* Smarty version 2.6.6, created on 2014-01-04 12:05:52
         compiled from E:/xampp/htdocs/stf/app_contents/user_manager/user_manager.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'E:/xampp/htdocs/stf/app_contents/user_manager/user_manager.html', 35, false),)), $this); ?>
<script language="JavaScript">
    function thisSelect(inputobj, linkname, format) 
    {
        var thisDate = new CalendarPopup();
        thisDate.showNavigationDropdowns();
        thisDate.select(inputobj,linkname, format);
    }
</script>

<body>
    <form name="userManagerForm" action="<?php echo $this->_tpl_vars['SCRIPT_NAME']; ?>
" method="POST" onsubmit="return doFormSubmit();" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $this->_tpl_vars['uid']; ?>
">
        <input type="hidden" id="psw" name="psw" value="<?php echo $this->_tpl_vars['password']; ?>
">
        
        <div id="body-full">     <!-- body-full starts here-->
            <div class="wrap">   <!-- wrap class starts here-->
                <div class="body-block" style="padding-bottom:40px;">    <!-- body-block starts here-->
                    <div class="left">
                            <table  border="1" width="840px">
                                <tr>
                                    <td width="50%">
                                        <div id="userinfo">
                                            <h1>User Information</h1>
                                            <br>
                                            <p><?php echo $this->_tpl_vars['USER_FIRST']; ?>
 <?php echo $this->_tpl_vars['USER_LAST']; ?>
</p>
                                            <p>Application Status: <?php echo $this->_tpl_vars['application_status']; ?>
</p>
                                            <p>Submission Date : <?php echo $this->_tpl_vars['submit_date']; ?>
</p>
                                        </div>    
                                    </td>
                                    <td width="50%" valign="top">
                                        <div id="sessioninfo">
                                            <h1>Help Information</h1>
                                            <br>
                                            <p>Session: <?php echo $this->_tpl_vars['sessionInfo']->year; ?>
</p>
                                            <p>Session Start: <?php echo ((is_array($_tmp=$this->_tpl_vars['sessionInfo']->session_start_date)) ? $this->_run_mod_handler('date_format', true, $_tmp, '%m-%d-%Y') : smarty_modifier_date_format($_tmp, '%m-%d-%Y')); ?>
</p>
                                            <p>Session End: <?php echo ((is_array($_tmp=$this->_tpl_vars['sessionInfo']->session_end_date)) ? $this->_run_mod_handler('date_format', true, $_tmp, '%m-%d-%Y') : smarty_modifier_date_format($_tmp, '%m-%d-%Y')); ?>
</p>
                                        </div>
                                    </td>
                                </tr>    
                            </table>
                    </div>
                </div>
            </div>
        </div>  <!-- wrap class ends here-->
       
    </form>
</body>
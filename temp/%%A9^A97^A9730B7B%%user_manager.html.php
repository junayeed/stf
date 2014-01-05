<?php /* Smarty version 2.6.6, created on 2014-01-05 12:31:48
         compiled from E:/xampp/htdocs/stf/app_contents/user_manager/user_manager.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'E:/xampp/htdocs/stf/app_contents/user_manager/user_manager.html', 31, false),)), $this); ?>
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
                        <div class="row">
                            <div class="col-md-6" style="float: left">
                                <div class="box-widget">
                                    <div class="widget-head clearfix">
                                        <h4>My Information</h4>
                                    </div>
                                    <div class="widget-container">
                                        <div class="widget-block">
                                            <div class="widget-content box-padding">
                                                <address>
                                                    <strong><?php echo $this->_tpl_vars['USER_FIRST']; ?>
 <?php echo $this->_tpl_vars['USER_LAST']; ?>
</strong><br><br>
                                                        <p class="text-info" >Application Status:  <strong><?php echo $this->_tpl_vars['application_status']; ?>
 </strong></p>
                                                        <p class="text-warning">Submission Date :  <strong><?php echo ((is_array($_tmp=$this->_tpl_vars['submit_date'])) ? $this->_run_mod_handler('date_format', true, $_tmp, '%d/%m/%Y') : smarty_modifier_date_format($_tmp, '%d/%m/%Y')); ?>
 </strong></p>
                                                </address>                                                                
                                                <address>
                                                    <strong>Email</strong><br>
                                                    <a href="mailto:#"><?php echo $this->_tpl_vars['email']; ?>
</a>
                                                </address>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6" style="float: right">
                                <div class="box-widget">
                                    <div class="widget-head clearfix">
                                        <h4>Important Notes</h4>
                                    </div>
                                    <div class="widget-container">
                                        <div class="widget-block">
                                            <div class="widget-content box-padding">
                                                <p class="muted">Current Session is :<b><?php echo $this->_tpl_vars['sessionInfo']->year; ?>
</b></p><br>
                                                <p class="text-warning">
                                                    Application Date is: <b><?php echo ((is_array($_tmp=$this->_tpl_vars['sessionInfo']->session_start_date)) ? $this->_run_mod_handler('date_format', true, $_tmp, '%d/%m/%Y') : smarty_modifier_date_format($_tmp, '%d/%m/%Y')); ?>
</b> to <b><?php echo ((is_array($_tmp=$this->_tpl_vars['sessionInfo']->session_start_date)) ? $this->_run_mod_handler('date_format', true, $_tmp, '%d/%m/%Y') : smarty_modifier_date_format($_tmp, '%d/%m/%Y')); ?>
</b>
                                                </p>
                                                <br>
                                                <p class="text-info">
                                                    You have submit your application in between the above date. If you submit your application after the given date then your application will no longer accepted for review
                                                </p><br />
                                                <p class="text-success">
                                                    Please provide the correct information.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>  <!-- wrap class ends here-->
       
    </form>
</body>
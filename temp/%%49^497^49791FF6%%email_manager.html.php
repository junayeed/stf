<?php /* Smarty version 2.6.6, created on 2015-05-27 11:05:30
         compiled from E:/xampp/htdocs/stf/app_contents/email_manager/email_manager.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'stripslashes', 'E:/xampp/htdocs/stf/app_contents/email_manager/email_manager.html', 24, false),)), $this); ?>
<script language="JavaScript">
    function thisSelect(inputobj, linkname, format) 
    {
        var thisDate = new CalendarPopup();
        thisDate.showNavigationDropdowns();
        thisDate.select(inputobj,linkname, format);
    }
</script>

<body>
    <form name="sessionForm" id="sessionForm" action="<?php echo $this->_tpl_vars['SCRIPT_NAME']; ?>
" method="POST" onsubmit="return doFormSubmit();" enctype="multipart/form-data">
        <!-- HIDDERN FIELDS -->
        <input type="hidden" name="id" value="<?php echo $this->_tpl_vars['id']; ?>
">
        <input type="hidden" name="cmd" value="add">
        <!-- HIDDEN FIELDS -->

        <div id="body-full">   <!-- body-full class starts here -->
            <div class="wrap">  <!-- wrap class starts here -->
                <div class="body-block" style="padding-bottom:30px;">  <!-- body-block class starts here -->
                    <div class="left">    <!-- left class starts here-->
                        <h3><span>Template Details</span></h3>
                        <div class="font3 prepand3"><label id="session_year_lbl">Template Name</label></div>
                        <div class="prepand">
                            <input type="text" name="template_name" id="template_name" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['template_name'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
" class="inputbox3 W300" />
                        </div>
                        <div class="font3 prepand3"><label id="session_year_lbl">Email Subject</label></div>
                        <div class="prepand">
                            <input type="text" name="mail_subject" id="mail_subject" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['mail_subject'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
" class="inputbox3 W300" />
                        </div>
                        <div class="font3 prepand3"><label id="session_year_lbl">Email Subject</label></div>
                        <div class="prepand">
                            <textarea name="mail_body" id="mail_body" class="inputbox3 W525 H120"></textarea>
                        </div>
                        <div class="font3 prepand3"><label id="session_year_lbl">Recipients</label></div>
                        <div class="prepand">
                            <select class="inputbox3 W75">
                                <option></option>
                            </select>
                        </div>
                    </div>  <!-- left class ends here-->                    
                    
                    <div class="right">  <!-- right class starts here-->
                        <div class="right-body">  <!-- right-body class starts here-->
                            <h3><span>Email Templates</span></h3>
                        </div>                             <!-- right-body class starts here-->
                        <div class="customers-body">   <!--customer body starts here-->
                            <div class="font3 prepand">
                                <a href="javascript:void(0);" onClick="alert('Accepted');">
                                    <label class="accept-label W115 font13">Accepted</label>
                                </a>
                            </div>
                            <div class="font3 prepand3">
                                <a href="javascript:void(0);" onClick="alert('Accepted');">
                                    <label class="reject-label W115 font13 margin-top-10">Rejected</label>
                                </a>
                            </div>
                            <div class="font3 prepand">
                                <a href="javascript:void(0);" onClick="alert('Accepted');">
                                    <label class="pending-label W115 font13 margin-top-10">Pending</label>
                                </a>
                            </div>
                            <div class="font3 prepand">
                                <a href="javascript:void(0);" onClick="alert('Accepted');">
                                    <label class="not-submitted-label W115 font13 margin-top-10">Not Submitted</label>
                                </a>
                            </div>
                            </div>
                        </div>  <!--customer body ends here-->
                    </div>      <!-- right class ends here-->
                    
                </div>          <!-- body-block class ends here -->
            </div>              <!-- wrap class ends here -->
        </div>                  <!-- body-full class ends here -->
    </form>
</body>
<?php /* Smarty version 2.6.6, created on 2013-12-15 00:53:59
         compiled from E:/xampp/htdocs/stf/app_contents/customer_manager/customer_manager.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'E:/xampp/htdocs/stf/app_contents/customer_manager/customer_manager.html', 39, false),array('modifier', 'stripslashes', 'E:/xampp/htdocs/stf/app_contents/customer_manager/customer_manager.html', 72, false),)), $this); ?>
<script language="JavaScript">
    function thisSelect(inputobj, linkname, format) 
    {
        var thisDate = new CalendarPopup();
        thisDate.showNavigationDropdowns();
        thisDate.select(inputobj,linkname, format);
    }
</script>
<!-- Add fancyBox main JS and CSS files -->
<script type="text/javascript" src="/ext/jquery-ui/js/jquery.fancybox.js?v=2.1.4"></script>
<link rel="stylesheet" type="text/css" href="/ext/jquery-ui/css/jquery.fancybox.css?v=2.1.4" media="screen" />
<link rel="stylesheet" type="text/css" href="/ext/jquery-ui/css/ui-lightness/jquery.ui.all.css"  media="screen" />
<link rel="stylesheet" href="/ext/jquery-ui/css/jquery-ui.css" />

<body>
    <form name="customerForm" id="customerForm" action="<?php echo $this->_tpl_vars['SCRIPT_NAME']; ?>
" method="POST" enctype="multipart/form-data" onsubmit="return doFormSubmit();" >
        <!-- HIDDEN FIELDS -->
        <input type="hidden" name="id" value="<?php echo $this->_tpl_vars['id']; ?>
">
        <input type="hidden" name="cmd" value="add">
        <!-- HIDDEN FIELDS -->
        
        <div id="body-full">   <!-- body-full class starts here -->
            <div class="wrap">  <!-- wrap class starts here -->
                <div class="body-block" style="padding-bottom:30px;">  <!-- body-block class starts here -->
                    <div class="left">    <!-- left class starts here-->
                        
                        <h3><span>Search Customers</span></h3>
                        <div class="customers-search-body">  <!--customer body starts here-->
                            <div class="company-name">
                                <div class="font2">Company name</div>
                                <div class="prepand">
                                    <input name="company_name_search" id="company_name_search" type="text" class="inputbox3 W127" />
                                </div>
                            </div>
                            <div class="company-status">
                                <div class="font2">Status</div>
                                <div class="prepand">
                                    <select name="status" id="status" class="combo1 W127">
                                        <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['status_list'],'output' => $this->_tpl_vars['status_list'],'selected' => $this->_tpl_vars['status']), $this);?>

                                    </select>
                                </div>
                            </div>
                            <div class="prepand2">
                            <table width="127" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td class="prepand2">
                                        <input type="button" class="inputbox-submit W57" id="search" name="search" value="SEARCH" onclick="doCustomerSearch();">
                                    </td>
                                    <td class="prepand2">
                                        <a href="javascript:void(0);" onClick="doClearForm();">
                                            <img src="/app_contents/common/images/clear.gif" alt="Clear" />
                                        </a>
                                    </td>
                                </tr>
                            </table>
                                </div>
                        </div>     <!--customer body ends here-->
                        
                        <h3 style="clear: both;"><span>Available Customer(s)</span></h3>
                        <iframe id="customerFrame"  src="<?php echo $this->_tpl_vars['SCRIPT_NAME']; ?>
?cmd=list" scrolling="no" width="100%" frameborder="0" onload='resizeIframe(this);'></iframe>
                        <!--<div class="viewmore"><a href="javascript:void(0)">view more</a></div>-->
                    </div>  <!-- left class ends here-->

                    <div class="right">  <!-- right class starts here-->
                        
                        <div class="right-body">  <!-- right-body class starts here-->
                            <h3><span>Customer Import</span></h3>
                        </div>
                        <div class="customers-body">   <!--customer body starts here-->
                            <div class="font2"><label id="first_name_lbl">Capsule ID</label></div>
                            <div class="prepand">
                                <input type="text" name="capsule_id" id="capsule_id" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['capsule_id'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
" class="inputbox2" />
                            </div>
                            
                            <!-- capsule_customer starts here-->
                            <div id="capsule_customer" style="display: none;">
                                <div class="font2">
                                    <label id="capsule_first_name_lbl">First Name: </label><label id="capsule_first_name"></label>
                                </div>
                                <div class="font2">
                                    <label id="capsule_last_name_lbl">Last Name: </label><label id="capsule_last_name"></label>
                                </div>
                                <div class="font2">
                                    <label id="capsule_company_name_lbl">Company Name: </label><label id="capsule_company_name"></label>
                                </div>
                                <div class="font2">
                                    <label id="capsule_email_lbl">Email: </label><label id="capsule_email"></label>
                                </div>
                                <div class="font2">
                                    <label id="capsule_address_lbl">Address: </label><label id="capsule_address"></label>
                                </div>
                                <div class="font2">
                                    <label id="capsule_town_lbl">Town: </label><label id="capsule_town"></label>
                                </div>
                                <div class="font2">
                                    <label id="capsule_county_lbl">County: </label><label id="capsule_county"></label>
                                </div>
                                <div class="font2">
                                    <label id="capsule_postcode_lbl">Postcode: </label><label id="capsule_postcode"></label>
                                </div>
                            </div>
                            <!-- capsule_customer ends here-->
                            <table width="140" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td width="" class="prepand2">
                                        <input type="button" class="inputbox-submit" id="send" name="send" value="Get" onClick="getCapsuleInfo();">
                                    </td>
                                    <td width="" class="prepand2" align="center">
                                        <a href="javascript:void(0)" onClick="doClearCustomerImportForm();"><img src="/app_contents/common/images/reset.gif" alt="Reset" /></a>
                                    </td>
                                </tr>
                            </table>
                        </div>  
                        
                        <div class="right-body prepand4">  <!-- right-body class starts here-->
                            <h3><span>Customer Details</span></h3>
                        </div>                             <!-- right-body class starts here-->

                        <div class="customers-body">   <!--customer body starts here-->
                            <!--<div class="font2"><label id="first_name_lbl">First Name</label></div>
                            <div class="prepand">
                                <input type="text" name="first_name" id="first_name" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['first_name'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
" class="inputbox2" />
                            </div>
                            <div class="font2 prepand"><label id="last_name_lbl">Last Name</label></div>
                            <div class="prepand">
                                <input type="text" name="last_name" id="last_name" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['last_name'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
" class="inputbox2" />
                            </div> -->
                            <div class="font2"><label id="company_name_lbl">Company Name</label></div>
                            <div class="prepand">
                                <input type="text" name="company_name" id="company_name" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['company_name'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
" onchange="isCompanyExists();" class="inputbox2" />
                            </div>
                            <div class="font2 prepand"><label id="email_lbl">Email</label></div>
                            <div class="prepand">
                                <input type="text" name="email" id="email" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['email'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
" onchange="checkDuplicateEmail();" class="inputbox2" />
                            </div>
                            <div class="font2 prepand"><label id="address_lbl">Address</label></div>
                            <div class="prepand">
                                <textarea name="address" id="address" class="inputbox2 H50"><?php echo ((is_array($_tmp=$this->_tpl_vars['address'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
</textarea>
                            </div>
                            <div class="font2 prepand"><label id="town_lbl">Town</label></div>
                            <div class="prepand">
                                <input type="text" name="town" id="town" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['town'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
" class="inputbox2" />
                            </div>
                            <div class="font2 prepand"><label id="county_lbl">County</label></div>
                            <div class="prepand">
                                <select name="county" id="county" class="inputbox2">
                                    <option value="">-- Select --</option>
                                    <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['county_list'],'selected' => $this->_tpl_vars['county']), $this);?>

                                </select>
                            </div>
                            <div class="font2 prepand" id="postcode_lbl">Postcode</div>
                            <div class="prepand">
                                <input type="text" name="postcode" id="postcode" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['postcode'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
" class="inputbox2" />
                            </div>
                            <div class="font2 prepand"><label id="status_lbl">Status</label></div>
                            <div class="prepand">
                                <select name="status" id="status" class="inputbox2">
                                    <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['status_list'],'output' => $this->_tpl_vars['status_list'],'selected' => $this->_tpl_vars['status']), $this);?>

                                </select>
                            </div>
                            <table width="140" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td width="" class="prepand2">
                                        <input type="submit" class="inputbox-submit" id="send" name="send" value="SAVE">
                                    </td>
                                    <td width="" class="prepand2" align="center">
                                        <a href="javascript:void(0)" onClick="doResetForm(this);"><img src="/app_contents/common/images/reset.gif" alt="Reset" /></a>
                                    </td>
                                </tr>
                            </table>
                        </div>  <!--customer body ends here-->
                    </div>      <!-- right class ends here-->
                </div>          <!-- body-block class ends here -->
            </div>              <!-- wrap class ends here -->
        </div>                  <!-- body-full class ends here -->
    </form>
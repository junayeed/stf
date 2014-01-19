<?php /* Smarty version 2.6.6, created on 2014-01-19 08:27:52
         compiled from E:/xampp/htdocs/stf/app_contents/airfare_manager/airfare_manager.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'E:/xampp/htdocs/stf/app_contents/airfare_manager/airfare_manager.html', 62, false),)), $this); ?>
<script language="JavaScript">
    function thisSelect(inputobj, linkname, format) 
    {
        var thisDate = new CalendarPopup();
        thisDate.showNavigationDropdowns();
        thisDate.select(inputobj,linkname, format);
    }
</script>

<body>
    <form name="airfareForm" id="airfareForm" action="<?php echo $this->_tpl_vars['SCRIPT_NAME']; ?>
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
                        <h3><span>Collected Air Fare from Local Sources</span></h3>
                        <div class="customer-left-body">   <!--customer-left-body starts here-->
                            <table width="760" cellpadding="0" cellspacing="0" border="0">
                                <thead>
                                    <tr>
                                        <td width="50">Country</td>
                                        <td width="50">Air Fare</td>
                                        <td width="90">Source</td>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if (count($_from = (array)$this->_tpl_vars['country_list'])):
    foreach ($_from as $this->_tpl_vars['item']):
?>
                                    <tr>
                                        <td class="border"><?php echo $this->_tpl_vars['item']->country_name; ?>
</td>
                                        <td class="border"><input type="text" name="local_fare_<?php echo $this->_tpl_vars['item']->country; ?>
" id="local_fare_<?php echo $this->_tpl_vars['item']->country; ?>
" value="<?php echo $this->_tpl_vars['item']->local_fare; ?>
" class="inputbox3 W127" onkeypress="return isNumberKey(event);"></td>
                                        <td class="border"><input type="text" name="source_<?php echo $this->_tpl_vars['item']->country; ?>
" id="source_<?php echo $this->_tpl_vars['item']->country; ?>
" value="<?php echo $this->_tpl_vars['item']->source; ?>
" class="inputbox3 W200"></td> 
                                    </tr>
                                <?php endforeach; unset($_from); endif; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td align="right" colspan="3">
                                            <input type="submit" class="inputbox-submit" id="save" name="send" value="SAVE">
                                            <input type="reset" class="inputbox-submit" id="clear" name="clear" value="CLEAR">
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="viewmore"> </div>
                    </div>  <!-- left class ends here-->                    
                    
                    <div class="right">  <!-- right class starts here-->
                        <div class="right-body">  <!-- right-body class starts here-->
                            <h3><span>Session Details</span></h3>
                        </div>                             <!-- right-body class starts here-->
                        <div class="customers-body">   <!--customer body starts here-->
                            <div class="font3 prepand"><label id="session_year_lbl">Session Year</label></div>
                            <div class="prepand">
                                <select class="combo1 W127" name="session_year" id="session_year" onChange="searchAirFare();">
                                    <option value="">-- Select --</option>
                                    <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['session_year_list'],'selected' => $this->_tpl_vars['session_year']), $this);?>

                                </select>
                            </div>
                        </div>  <!--customer body ends here-->
                    </div>      <!-- right class ends here-->
                </div>          <!-- body-block class ends here -->
            </div>              <!-- wrap class ends here -->
        </div>                  <!-- body-full class ends here -->
    </form>
</body>
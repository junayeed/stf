<?php /* Smarty version 2.6.6, created on 2015-05-02 16:42:56
         compiled from E:/xampp/htdocs/stf/app_contents/report_manager/report_manager.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'number_format', 'E:/xampp/htdocs/stf/app_contents/report_manager/report_manager.html', 55, false),)), $this); ?>
<!-- Add fancyBox main JS and CSS files -->
<script type="text/javascript" src="/ext/jquery-ui/js/jquery.fancybox.js?v=2.1.4"></script>
<link rel="stylesheet" type="text/css" href="/ext/jquery-ui/css/jquery.fancybox.css?v=2.1.4" media="screen" />
<link rel="stylesheet" type="text/css" href="/ext/jquery-ui/css/ui-lightness/jquery.ui.all.css"  media="screen" />
<link rel="stylesheet" href="/ext/jquery-ui/css/jquery-ui.css" />

<script language="JavaScript">
    function thisSelect(inputobj, linkname, format) 
    {
        var thisDate = new CalendarPopup();
        thisDate.showNavigationDropdowns();
        thisDate.select(inputobj,linkname, format);
    }
</script>

<script> var AppIDs     = []; </script>

<body onLoad="showSliders();">
    <form name="applicantManagerForm" action="<?php echo $this->_tpl_vars['SCRIPT_NAME']; ?>
" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $this->_tpl_vars['uid']; ?>
">
        <input type="hidden" id="cmd" name="cmd" value="add">
        
        <div id="body-full">     <!-- body-full starts here-->
            <div class="wrap">   <!-- wrap class starts here-->
                <div class="body-block" style="padding-bottom:40px;">    <!-- body-block starts here-->
                    <div class="left-full">    <!-- left class starts here-->
                        <h3 style="clear: both;" class="prepand3"><span>Accepted Applicant(s)</span>  <a href="?cmd=pdf" target="_new"><img src="/app_contents/common/images/pdf.png"></a></h3>
                        <div class="customer-left-body"><h6>All Amounts are in BDT</h6>
                            <table width="940" cellpadding="0" cellspacing="0" border="0">
                                <thead>
                                    <tr>
                                        <td width="260">Applicant Name</td>
                                        <td width="260">Guardian Name</td>
                                        <td width="100">Guardian Occupation</td>
                                        <td width="100">Guardian Income</td>
                                        <td width="110">Approved Amount</td>
<!--                                        <td width="70">Collected Fare (Local Sources)</td>
                                        <td width="70">Base Fare</td>
                                        <td width="100">Grant Amount</td>-->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $this->assign('grand_total', '0'); ?>
                                    <?php if (count($_from = (array)$this->_tpl_vars['list'])):
    foreach ($_from as $this->_tpl_vars['okey'] => $this->_tpl_vars['country_list']):
?>
                                        <tr class="">
                                            <td colspan="5"><div class="font"><?php echo $this->_tpl_vars['okey']; ?>
</div></td>
                                        </tr>
                                        <?php $this->assign('country_total', '0'); ?>
                                        <?php if (count($_from = (array)$this->_tpl_vars['country_list'])):
    foreach ($_from as $this->_tpl_vars['city_key'] => $this->_tpl_vars['city_list']):
?>
                                            <?php if (count($_from = (array)$this->_tpl_vars['city_list'])):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
                                            <tr>
                                                <td class="border"><?php echo $this->_tpl_vars['item']->name; ?>
</td>
                                                <td class="border"><?php echo $this->_tpl_vars['item']->guardian_name; ?>
</td>
                                                <td class="border"><?php echo $this->_tpl_vars['item']->guardian_occupation; ?>
</td>
                                                <td class="border" align="right"><?php echo ((is_array($_tmp=$this->_tpl_vars['item']->guardian_income)) ? $this->_run_mod_handler('number_format', true, $_tmp, '2') : number_format($_tmp, '2')); ?>
</td>
                                                <td class="border" align="right"><?php echo ((is_array($_tmp=$this->_tpl_vars['item']->grant_amount)) ? $this->_run_mod_handler('number_format', true, $_tmp, '2') : number_format($_tmp, '2')); ?>
</td>
                                            </tr>
                                            <?php $this->assign('country_total', $this->_tpl_vars['country_total']+$this->_tpl_vars['item']->grant_amount); ?>
                                            <?php $this->assign('grand_total', $this->_tpl_vars['grand_total']+$this->_tpl_vars['item']->grant_amount); ?>
                                            <?php endforeach; unset($_from); endif; ?>
                                        <?php endforeach; unset($_from); endif; ?>
                                        <tr>
                                            <td colspan="4" class="border" align="right"><span class="span-text">Total (<?php echo $this->_tpl_vars['okey']; ?>
): </span></td>
                                            <td class="border" align="right"><span class="span-text"><?php echo ((is_array($_tmp=$this->_tpl_vars['country_total'])) ? $this->_run_mod_handler('number_format', true, $_tmp, '2') : number_format($_tmp, '2')); ?>
</span></td>
                                        </tr>
                                    <?php endforeach; unset($_from); endif; ?>
                                        <tr>
                                            <td colspan="4" class="border" align="right"><span class="span-text FONT16">Grand Total: </span></td>
                                            <td class="border" align="right"><span class="span-text FONT16"><?php echo ((is_array($_tmp=$this->_tpl_vars['grand_total'])) ? $this->_run_mod_handler('number_format', true, $_tmp, '2') : number_format($_tmp, '2')); ?>
</span></td>
                                        </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>  <!-- left class ends here-->
                </div>           <!-- body-block ends here-->
            </div>               <!-- wrap class ends here-->
        </div>                   <!-- body-full ends here-->
    </form>
</body>
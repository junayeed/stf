<?php /* Smarty version 2.6.6, created on 2013-12-13 00:43:51
         compiled from E:/xampp/htdocs/stf/app_contents/standard/user_home/dashboard.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'number_format', 'E:/xampp/htdocs/stf/app_contents/standard/user_home/dashboard.html', 38, false),array('modifier', 'truncate', 'E:/xampp/htdocs/stf/app_contents/standard/user_home/dashboard.html', 74, false),)), $this); ?>
<!-- HIGHCHARTS FILES-->
<script src="/ext/Highcharts-3.0.7/js/highcharts.js" type="text/javascript"></script>
<script src="/ext/Highcharts-3.0.7/js/modules/exporting.js"></script>
<script language="JavaScript" src="/app_contents/common/js/charts.js"></script>

<script>
    var companyArray         = [];
    var companyTotalArray    = [];
    var monthArray           = [];
    var monthlyTotalArray    = [];
    
    <?php if (count($_from = (array)$this->_tpl_vars['top_customers'])):
    foreach ($_from as $this->_tpl_vars['item']):
?> 
        companyArray.push('<?php echo $this->_tpl_vars['item']->company_name; ?>
');
        companyTotalArray.push(<?php echo $this->_tpl_vars['item']->total_sum; ?>
);
    <?php endforeach; unset($_from); endif; ?>
    
    <?php if (count($_from = (array)$this->_tpl_vars['yearly_income'])):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
        monthArray.push('<?php echo $this->_tpl_vars['item']['month']; ?>
');
        monthlyTotalArray.push(<?php echo $this->_tpl_vars['item']['total']; ?>
);
    <?php endforeach; unset($_from); endif; ?>
    //alert(companyTotalArray);
</script>
<!-- body-full start here-->
<div id="body-full"> 
    <div class="wrap">  <!--wrap class start here -->
        <div class="body-block">  <!-- body-block class starts here-->
            <div class="left">   <!-- left class starts here-->
                <form name="userHomeForm" action="<?php echo $this->_tpl_vars['SCRIPT_NAME']; ?>
" method="POST">
                <div class="row padding2 prepand"> <!-- statistics row start here-->
                    <div class="col-sm-3">
                        <div class="box_stat box_pos">
                            <h4><?php echo $this->_tpl_vars['total_customer']; ?>
</h4>
                            <small>Active Customer(s)</small>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="box_stat box_pos">
                            <h4>₤ <?php echo ((is_array($_tmp=$this->_tpl_vars['total_sale'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : number_format($_tmp, 2, ".", ",")); ?>
</h4>
                            <small>Total Sale</small>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="box_stat box_pos">
                            <h4><?php echo $this->_tpl_vars['total_order']; ?>
</h4>
                            <small>Total Order(s)</small>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="box_stat box_pos">
                            <h4><?php echo $this->_tpl_vars['total_space']; ?>
</h4>
                            <small>Booked <?php echo $this->_tpl_vars['issue_month']; ?>
</small>
                        </div>
                    </div>
                </div> <!-- summary row start here-->
                                
                <h3><span>Yearly Sale</span></h3>
        	<div class="left-body">  <!-- left-body class starts here -->
                    <div id="container" style="width:100%"></div>
                </div>
                </form>
                
                <h3 class="prepand2"><span>Top 10 Customers</span></h3>
        	<div class="left-body">  <!-- left-body class starts here -->
                    <div id="top_customers" style="width:100%"></div>
                </div>
               
            </div>    <!-- left class ends here-->
            <div class="right">  <!-- right class starts here-->
                <h3><span>Customers</span></h3>
                <div class="customers-body">
                    <ul>
                        <?php if (count($_from = (array)$this->_tpl_vars['customer_list'])):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
                            <li>
                                <a href="/app/order_manager/order_manager.php?customer_id=<?php echo $this->_tpl_vars['key']; ?>
&cmd=edit"><?php echo ((is_array($_tmp=$this->_tpl_vars['item'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 45) : smarty_modifier_truncate($_tmp, 45)); ?>
</a>
                            </li>
                        <?php endforeach; unset($_from); endif; ?>
                    </ul>
                </div>
            </div>  <!-- right class ends here-->
        </div><!-- body-block class starts here-->
    </div>
</div>
<script> showTop10customers(); showYearWiseCharts(); </script>

<!--
<table width="760" cellpadding="0" cellspacing="0" border="1">
                	<thead>
                            <tr>
                                <td width="8">&nbsp;</td>
                                <td width="50"><span>Order</span></td>
                                <td width="127"><span>Customer</span></td>
                                <td width="45"><span>Code</span></td>
                                <td width="74"><span>Magazine</span></td>
                                <td width="57"><span>Start</span></td>
                                <td width="67"><span>End</span></td>
                                <td width="42"><span>A</span></td>
                                <td width="50" class="TEXTRIGHT"><span>Page</span></td>
                                <td width="43" class="TEXTRIGHT"><span>Qty</span></td>
                                <td width="51" class="TEXTRIGHT"><span>Price</span></td>
                                <td width="46" class="TEXTRIGHT"><span>Disc</span></td>
                                <td width="54" class="TEXTRIGHT"><span>Total</span></td>
                                <td width="46" class="TEXTCENTER"><span>Status</span></td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($_from = (array)$this->_tpl_vars['order_list'])):
    foreach ($_from as $this->_tpl_vars['item']):
?>
                            <?php if ($this->_tpl_vars['item']->product_code != ''): ?>
                                <tr>
                                    <td width="8" class="prepand3">&nbsp;</td>
                                    <td width="50" class="prepand3"><?php echo $this->_tpl_vars['item']->id; ?>
</td>
                                    <td width="127" class="prepand3"><a href="/app/order_manager/order_manager.php?customer_id=<?php echo $this->_tpl_vars['item']->customer_id; ?>
&cmd=edit"><?php echo $this->_tpl_vars['item']->company_name; ?>
</td>
                                    <td width="45" class="prepand3"><?php echo $this->_tpl_vars['item']->product_code; ?>
</td>
                                    <td width="74" class="prepand3"><?php echo $this->_tpl_vars['item']->magazine_abvr; ?>
</td>
                                    <td width="57" class="prepand3"><?php echo $this->_tpl_vars['item']->start_month; ?>
</td>
                                    <td width="67" class="prepand3"><?php echo $this->_tpl_vars['item']->end_month; ?>
</td>
                                    <td width="42" class="prepand3"><?php echo $this->_tpl_vars['item']->alternative; ?>
</td>
                                    <td width="50" class="prepand3 TEXTRIGHT"><?php echo $this->_tpl_vars['item']->page; ?>
</td>
                                    <td width="43" class="prepand3 TEXTRIGHT"><?php echo $this->_tpl_vars['item']->qty; ?>
</td>
                                    <td width="51" class="prepand3 TEXTRIGHT"><?php echo ((is_array($_tmp=$this->_tpl_vars['item']->unit_price)) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : number_format($_tmp, 2, ".", ",")); ?>
</td>
                                    <td width="46" class="prepand3 TEXTRIGHT"><?php echo $this->_tpl_vars['item']->discount; ?>
%</td>
                                    <td width="54" class="prepand3 TEXTRIGHT"><?php echo ((is_array($_tmp=$this->_tpl_vars['item']->total)) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : number_format($_tmp, 2, ".", ",")); ?>
</td>
                                    <td width="46" class="prepand3 TEXTCENTER"><span><?php echo $this->_tpl_vars['item']->status; ?>
</span></td>
                                </tr>
                                <tr>
                                    <td class="border">&nbsp;</td>
                                    <td class="border">&nbsp;</td>
                                    <td class="border" colspan="5"><div class="font"><?php echo $this->_tpl_vars['item']->description; ?>
</div></td>
                                    <td class="border">&nbsp;</td>
                                    <td class="border">&nbsp;</td>
                                    <td class="border">&nbsp;</td>
                                    <td class="border">&nbsp;</td>
                                    <td class="border">&nbsp;</td>
                                    <td class="border">&nbsp;</td>
                                    <td class="border">&nbsp;</td>
                                </tr>
                            <?php endif; ?>
                            <?php endforeach; unset($_from); endif; ?>
                        </tbody>                    
                    </table> -->
<?php /* Smarty version 2.6.6, created on 2013-12-13 21:17:40
         compiled from E:/xampp/htdocs/stf/app_contents/order_manager/order_manager.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'nl2br', 'E:/xampp/htdocs/stf/app_contents/order_manager/order_manager.html', 37, false),array('modifier', 'date_format', 'E:/xampp/htdocs/stf/app_contents/order_manager/order_manager.html', 58, false),array('modifier', 'truncate', 'E:/xampp/htdocs/stf/app_contents/order_manager/order_manager.html', 125, false),)), $this); ?>
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

<body>
<form name="orderManagerForm" action="<?php echo $this->_tpl_vars['SCRIPT_NAME']; ?>
" method="POST" onsubmit="return doFormSubmit();" enctype="multipart/form-data">    
    <input type="hidden" name="customer_id" id="customer_id" value="<?php echo $this->_tpl_vars['customer_id']; ?>
">
    <input type="hidden" name="schedule_ids" id="schedule_ids" value="">
    <input type="hidden" name="cmd" value="add">
    
    <div id="body-full">   <!-- body-full starts here-->
        <div class="wrap">   <!-- wrap class starts here-->
            <div class="body-block">   <!-- body-block starts here-->
                <div class="left">     <!-- left class starts here-->
                    <h3><span>Order Info</span></h3>
                    <div class="left-body">   <!-- left-body starts here-->
                        <div class="order-info-left">  <!-- order-info-left starts here-->
                            <ul>
                                <li class="company-icon no-icon">
                                    <a href="/app/customer_manager/customer_manager.php?id=<?php echo $this->_tpl_vars['customer_id']; ?>
&cmd=edit"><strong><?php echo $this->_tpl_vars['company_name']; ?>
</strong></a> 
                                    <a href="/app/customer_manager/customer_manager.php?id=<?php echo $this->_tpl_vars['customer_id']; ?>
&cmd=edit">
                                        <img src="/app_contents/common/images/pencile.png" alt="Image" style="float:right; margin-right: 10px; top: 10px; right: -3px; position: absolute; " />
                                    </a>
                                </li>
                                <li class="prepand email-icon"><?php echo $this->_tpl_vars['email']; ?>
</li>
                                <li class="prepand address-icon"><?php echo ((is_array($_tmp=$this->_tpl_vars['address'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</li>
                                <li class="no-icon"><?php echo $this->_tpl_vars['town']; ?>
</li>
                                <li class="no-icon"><?php echo $this->_tpl_vars['city_name']; ?>
</li>
                                <li class="no-icon"><?php echo $this->_tpl_vars['postcode']; ?>
</li>
                            </ul>
                        </div>   <!-- order-info-left ends here-->
                        <div class="order-info-mid">   <!-- order-info-mid starts here-->
                            <ul>
                                <li>Notes/Instructions 
                                    <a href="javascript:void(0);" onClick="openProductBox('customer_notes');">
                                        <img src="/app_contents/common/images/pencile.png" alt="Image" style="float:right; margin-right: 10px;" />
                                    </a>
                                </li>
                            </ul>
                            <p id="order_notes"><?php echo $this->_tpl_vars['order_notes']; ?>
</p>
                        </div>  <!-- order-info-mid ends here-->
                        <div class="order-info-right">
                            <ul>
                                <li>History</li>
                            </ul>
                            <?php if (count($_from = (array)$this->_tpl_vars['order_history'])):
    foreach ($_from as $this->_tpl_vars['item']):
?>
                                <p><?php echo ((is_array($_tmp=$this->_tpl_vars['item']->update_date)) ? $this->_run_mod_handler('date_format', true, $_tmp, '%d-%m-%Y') : smarty_modifier_date_format($_tmp, '%d-%m-%Y')); ?>
: Order <?php echo $this->_tpl_vars['item']->order_details_id; ?>
 <?php echo $this->_tpl_vars['item']->order_status; ?>
 by <?php echo $this->_tpl_vars['item']->uptdaed_by; ?>
</p> 
                            <?php endforeach; unset($_from); endif; ?>
                            <!--<p class="prepand"><a href="#">view more</a></p>-->
                        </div>
                        
                        <div class="left-body margin prepand4">  
                            <h3><span>Order Details</span></h3>
                        </div>
                        <div class="left-body">  <!-- left-body starts here-->
                            <table width="760" cellpadding="0" cellspacing="0" border="0" id="order_tbl">
                                <thead>
                                    <tr>
                                        <td width="4">&nbsp;</td>
                                        <td width="47"><span>Order</span></td>
                                        <td width="63"><span>Product</span></td>
                                        <td width="70"><span>Magazine</span></td>
                                        <!--<td width="133"><span>Item</span></td>-->
                                        <td width="82"><span>Start Mo</span></td>
                                        <td width="82"><span>End Mo</span></td>
                                        <td width="35" align="center"><span>A</span></td>
                                        <!--<td width="45"><span>Page</span></td>-->
                                        <td width="38"><span>Qty</span></td>
                                        <td width="70"><span>Unit Price</span></td>
                                        <td width="46"><span>Disc</span></td>
                                        <td width="75"><span>Order Total</span></td>
                                        <td width="53"><span>Status</span></td>
                                        <td width="50"><span>Action</span></td>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>   
                        </div>         <!-- left-body ends here-->   
                        <div class="viewmore">  <!-- viewmore div starts here -->
                            <table width="760">
                                <tr>
                                    <td class="font3 padding-left10">Total <?php echo $this->_tpl_vars['row_count']; ?>
 product(s) found</td>
                                    <td class="font3">Showing page <?php echo $this->_tpl_vars['page_no']+1; ?>
 out of <?php echo $this->_tpl_vars['page_count']; ?>
</td>
                                    <td class="font3 padding-right10" align="right"><?php if ($this->_tpl_vars['page_no'] != ''): ?><a href='<?php echo $this->_tpl_vars['SCRIPT_NAME']; ?>
?page_no=<?php echo $this->_tpl_vars['page_no']-1; ?>
&cmd=edit&customer_id=<?php echo $this->_tpl_vars['customer_id']; ?>
'>Previous</a><?php else: ?>Previous<?php endif; ?> | <?php if ($this->_tpl_vars['page_no'] == $this->_tpl_vars['page_count']-1): ?>Next<?php else: ?><a href='<?php echo $this->_tpl_vars['SCRIPT_NAME']; ?>
?page_no=<?php echo $this->_tpl_vars['page_no']+1; ?>
&cmd=edit&customer_id=<?php echo $this->_tpl_vars['customer_id']; ?>
'>Next</a><?php endif; ?></td>
                                </tr>    
                            </table>
                        </div>    <!-- viewmore div ends here -->
                        <div class="order-left prepand2">
                            <table width="760" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td width="644">
                                        <a href="javascript:void(0);"  onClick="addNewRow(1);"><img src="/app_contents/common/images/new-order.gif" alt="Add New Order" /></a>
                                    </td>
                                    <td width="56">
                                        <!--<a href="#"><img src="/app_contents/common/images/save2.gif" alt="Save" /></a>-->
                                        <input type="submit" value="SAVE" name="save" id="save" class="inputbox-white">
                                    </td>
                                    <td width="60">
                                        <!--<a href="#"><img src="/app_contents/common/images/cancel.gif" alt="Cancel" /></a>-->
                                        <input type="button" value="CANCEL" name="cancel" id="cancel" class="inputbox-white W57" onClick="doCancelOrder();">
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="right">
                    <h3><span>Customers</span></h3>
                    <div class="customers-body">    <!-- customer-body starts here-->
                        <ul>
                            <?php $this->assign('flag', 0); ?>
                            <?php if (count($_from = (array)$this->_tpl_vars['customer_list'])):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
                                <li>
                                    <a <?php if ($this->_tpl_vars['flag'] == 0): ?>class="fixed"<?php endif; ?> href="/app/order_manager/order_manager.php?customer_id=<?php echo $this->_tpl_vars['key']; ?>
&cmd=edit"><?php echo ((is_array($_tmp=$this->_tpl_vars['item'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 45) : smarty_modifier_truncate($_tmp, 45)); ?>
</a>
                                    <?php if ($this->_tpl_vars['flag'] == 0): ?>
                                    <ul>
                                        <li><a href="#" class="fixed">orders</a></li>
                                        <li><a href="javascript:void(0);" onClick="gotoURL('artwork_manager');">artwork</a></li>
                                        <li><a href="javascript:void(0);" onClick="gotoURL('schedule_manager');">schedule</a></li>
                                    </ul>
                                    <?php endif; ?>
                                    <?php $this->assign('flag', 1); ?>
                                </li>
                            <?php endforeach; unset($_from); endif; ?>
                        </ul>
                    </div>   <!-- customer-body starts here-->
                </div>
            </div> <!-- body-block starts here-->
        </div> <!-- wrap class starts here-->
    </div><!-- body-full starts here-->

    <div id="customer_notes" style="width:300px;display: none;">
        <h3>Add Notes/Instruction</h3>
        <textarea id="instructions" name="instructions" class="W300 H100"><?php echo $this->_tpl_vars['order_notes']; ?>
</textarea><br><br>
        <input type="button" class="inputbox-submit" name="submit" value="SAVE" onClick="saveOrderNote();">
    </div>
</form>
<script language="JavaScript">
    var productGroupOptns = '<option value=""></option>';
    var magazineOptns     = '<option value="0"></option>';
    var magazineArray     = new Array();
    var placed_by         = '<?php echo $this->_tpl_vars['USER_FIRST']; ?>
 <?php echo $this->_tpl_vars['USER_LAST']; ?>
';
    var placed_by_id      = '<?php echo $this->_tpl_vars['USER_ID']; ?>
';
    var product_str       = '<?php echo $this->_tpl_vars['full_product_str']; ?>
';
    
    <?php if (count($_from = (array)$this->_tpl_vars['product_group_list'])):
    foreach ($_from as $this->_tpl_vars['item']):
?> 
        productGroupOptns += '<option value="<?php echo $this->_tpl_vars['item']->id; ?>
"><?php echo $this->_tpl_vars['item']->pg_code; ?>
</option>';
    <?php endforeach; unset($_from); endif; ?>
           
    <?php if (count($_from = (array)$this->_tpl_vars['magazine_list'])):
    foreach ($_from as $this->_tpl_vars['item']):
?> 
        magazineOptns += '<option value="<?php echo $this->_tpl_vars['item']->id; ?>
"><?php echo $this->_tpl_vars['item']->magazine_abvr; ?>
</option>';
    <?php endforeach; unset($_from); endif; ?>
    
    <?php if (count($_from = (array)$this->_tpl_vars['magazine_list'])):
    foreach ($_from as $this->_tpl_vars['item']):
?> 
        magazineArray[<?php echo $this->_tpl_vars['item']->id; ?>
] = '<?php echo $this->_tpl_vars['item']->magazine_abvr; ?>
';
    <?php endforeach; unset($_from); endif; ?>
        
    <?php if (count($_from = (array)$this->_tpl_vars['product_list'])):
    foreach ($_from as $this->_tpl_vars['item']):
?>
        addNewRow(0);
        populateOrderDetails("<?php echo $this->_tpl_vars['item']->order_details_id; ?>
", "<?php echo $this->_tpl_vars['item']->product_id; ?>
", "<?php echo $this->_tpl_vars['item']->product_group; ?>
", "<?php echo $this->_tpl_vars['item']->description; ?>
", 
                             "<?php echo $this->_tpl_vars['item']->magazine_code; ?>
", "<?php echo $this->_tpl_vars['item']->start_month; ?>
", "<?php echo $this->_tpl_vars['item']->end_month; ?>
",  "<?php echo $this->_tpl_vars['item']->alternative; ?>
", "<?php echo $this->_tpl_vars['item']->qty; ?>
", 
                             "<?php echo $this->_tpl_vars['item']->unit_price; ?>
", "<?php echo $this->_tpl_vars['item']->discount; ?>
", "<?php echo $this->_tpl_vars['item']->total; ?>
", "<?php echo $this->_tpl_vars['item']->status; ?>
", "<?php echo $this->_tpl_vars['item']->placed_by; ?>
", 
                             "<?php echo $this->_tpl_vars['item']->order_date; ?>
", "<?php echo $this->_tpl_vars['item']->page; ?>
", "<?php echo $this->_tpl_vars['item']->product_code; ?>
", "<?php echo $this->_tpl_vars['item']->product_status; ?>
");
    <?php endforeach; unset($_from); endif; ?>
        
    //getMonthList();
    //getYearList();
</script>   
</body>
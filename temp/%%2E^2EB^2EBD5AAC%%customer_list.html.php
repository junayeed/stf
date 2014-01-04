<?php /* Smarty version 2.6.6, created on 2013-12-15 00:53:59
         compiled from E:/xampp/htdocs/stf/app_contents/customer_manager/customer_list.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'stripslashes', 'E:/xampp/htdocs/stf/app_contents/customer_manager/customer_list.html', 36, false),)), $this); ?>
<html>
    
<head>
<!-- CSS FILEs-->
<link rel="stylesheet" type="text/css" href="/app_contents/common/css/default.project.css" />
<link rel="stylesheet" type="text/css" href="/app_contents/common/css/Segoe-UI-Light-Bold/stylesheet.css"  />

<!-- JS FILES-->
<script language="JavaScript" src="<?php echo $this->_tpl_vars['SYSTEM_COMMON_JAVASCRIPT_DIR']; ?>
/common.js"></script>
<script language="JavaScript" src="/app_contents/common/js/messages.js"></script>
<script language="JavaScript" src="/app_contents/customer_manager/customer_manager.js"></script>
<script language="JavaScript" src="/app_contents/common/js/jquery.js"></script>

</head>

<body>
    <form method="post" action="<?php echo $this->_tpl_vars['SCRIPT_NAME']; ?>
?cmd=list" name="customerListForm">
        <div class="customer-left-body">   <!--customer-left-body starts here-->
            <table width="760" cellpadding="0" cellspacing="0" border="0">
                <thead>
                    <tr>
                        <td width="8">&nbsp;</td>
                        <!--<td width="143">Name</td>-->
                        <td width="165">Company Name</td>
                        <td width="169">Email</td>
                        <td width="90">County</td>
                        <td width="65">Postcode</td>
                        <td width="35" class="TEXTCENTER">Archive</td>
                        <td width="45" class="TEXTCENTER">Action</td>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($_from = (array)$this->_tpl_vars['list'])):
    foreach ($_from as $this->_tpl_vars['item']):
?>
                    <tr>
                        <td width="8" class="border">&nbsp;</td>
                        <!--<td width="143" class="border"><?php echo ((is_array($_tmp=$this->_tpl_vars['item']->first_name)) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['item']->last_name)) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
</td>-->
                        <td width="140" class="border"><a target="_parent" href="/app/order_manager/order_manager.php?cmd=edit&customer_id=<?php echo $this->_tpl_vars['item']->id; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['item']->company_name)) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
</a></td>
                        <td width="179" class="border"><?php echo ((is_array($_tmp=$this->_tpl_vars['item']->email)) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
</td>
                        <td width="90" class="border"><?php echo ((is_array($_tmp=$this->_tpl_vars['item']->city_name)) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
</td>
                        <td width="65" class="border"><?php echo ((is_array($_tmp=$this->_tpl_vars['item']->postcode)) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
</td>
                        <td width="35" class="border TEXTCENTER">
                            <!--<input type="checkbox" disabled <?php if ($this->_tpl_vars['item']->status == 'Archive'): ?>checked<?php endif; ?>>-->
                            <input id="demo_box_1" class="css-checkbox" type="checkbox" disabled <?php if ($this->_tpl_vars['item']->status == 'Archive'): ?>checked<?php endif; ?> />
                            <label for="demo_box_1" name="demo_lbl_1" class="css-label"></label>
                        </td>
                        <td width="45" class="border TEXTCENTER"><a href='<?php echo $this->_tpl_vars['SCRIPT_NAME']; ?>
?id=<?php echo $this->_tpl_vars['item']->id; ?>
&cmd=edit' target='_top' title="Edit"><img src="/app_contents/common/images/edit.png" alt="Edit"></a>&nbsp;&nbsp;<a onClick='return doConfirm("All the order details will be deleted.\n"+PROMPT_DELETE_CONFIRM);' href='<?php echo $this->_tpl_vars['SCRIPT_NAME']; ?>
?id=<?php echo $this->_tpl_vars['item']->id; ?>
&cmd=delete' target='_top' title="Delete"><img src="/app_contents/common/images/cross.png" alt="Delete"><a/></td>
                    </tr>
                    <?php endforeach; unset($_from); endif; ?>
                </tbody>
            </table>
        </div>   <!--customer-left-body ends here-->
        <div class="viewmore">
            <table width="760">
                <tr>
                    <td class="font3 padding-left10">Total <?php echo $this->_tpl_vars['row_count']; ?>
 customer(s) found</td>
                    <td class="font3">Showing page <?php echo $this->_tpl_vars['page_no']+1; ?>
 out of <?php echo $this->_tpl_vars['page_count']; ?>
</td>
                    <td class="font3 padding-right10" align="right"><?php if ($this->_tpl_vars['page_no'] != ''): ?>
                        <a href='<?php echo $this->_tpl_vars['SCRIPT_NAME']; ?>
?page_no=<?php echo $this->_tpl_vars['page_no']-1; ?>
&cmd=list&customer_name=<?php echo $this->_tpl_vars['customer_name']; ?>
&company_name=<?php echo $this->_tpl_vars['company_name_search']; ?>
&status=<?php echo $this->_tpl_vars['status']; ?>
'>Previous</a><?php else: ?>Previous<?php endif; ?> | 
                        <?php if ($this->_tpl_vars['page_no'] == $this->_tpl_vars['page_count']-1): ?>Next<?php else: ?><a href='<?php echo $this->_tpl_vars['SCRIPT_NAME']; ?>
?page_no=<?php echo $this->_tpl_vars['page_no']+1; ?>
&cmd=list&customer_name=<?php echo $this->_tpl_vars['customer_name']; ?>
&company_name=<?php echo $this->_tpl_vars['company_name_search']; ?>
&status=<?php echo $this->_tpl_vars['status']; ?>
'>Next</a><?php endif; ?></td>
                </tr>    
            </table>
        </div>
    </form>
</body>
</html>
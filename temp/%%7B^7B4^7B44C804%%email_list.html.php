<?php /* Smarty version 2.6.6, created on 2015-05-25 06:01:13
         compiled from E:/xampp/htdocs/stf/app_contents/email_manager/email_list.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'number_format', 'E:/xampp/htdocs/stf/app_contents/email_manager/email_list.html', 38, false),)), $this); ?>
<html>
    
<head>
<!-- CSS FILEs-->
<link rel="stylesheet" type="text/css" href="/app_contents/common/css/default.project.css" />
<link rel="stylesheet" type="text/css" href="/app_contents/common/css/Segoe-UI-Light-Bold/stylesheet.css"  />

<!-- JS FILES-->
<script language="JavaScript" src="/app_contents/common/js/common.js"></script>
<script language="JavaScript" src="/app_contents/common/js/messages.js"></script>
<script language="JavaScript" src="/app_contents/customer_manager/customer_manager.js"></script>
<script language="JavaScript" src="/app_contents/common/js/jquery.js"></script>

</head>

<body>
    <form method="post" action="<?php echo $this->_tpl_vars['SCRIPT_NAME']; ?>
?cmd=list" name="magazineListForm">
        <div class="customer-left-body">   <!--customer-left-body starts here-->
            <table width="760" cellpadding="0" cellspacing="0" border="0">
                <thead>
                    <tr>
                        <td width="30">Year</td>
                        <td width="50">Status</td>
                        <td width="50">Application Start</td>
                        <td width="50">Application End</td>
                        <td width="90">Bulk Amount (BDT)</td>
                        <td width="90">Percentage (BDT)</td>
                        <td width="30">Action</td>
                    </tr>
                </thead>
                <tbody>
                <?php if (count($_from = (array)$this->_tpl_vars['list'])):
    foreach ($_from as $this->_tpl_vars['item']):
?>
                    <tr>
                        <td class="border"><?php echo $this->_tpl_vars['item']->session_year; ?>
</td>
                        <td class="border"><?php echo $this->_tpl_vars['item']->session_status; ?>
</td>
                        <td class="border"><?php echo $this->_tpl_vars['item']->application_start_date; ?>
</td> 
                        <td class="border"><?php echo $this->_tpl_vars['item']->application_end_date; ?>
</td> 
                        <td class="border"><?php echo ((is_array($_tmp=$this->_tpl_vars['item']->scholarship_bulk_amount)) ? $this->_run_mod_handler('number_format', true, $_tmp, '2') : number_format($_tmp, '2')); ?>
</td> 
                        <td class="border"><?php echo ((is_array($_tmp=$this->_tpl_vars['item']->scholarship_percentage)) ? $this->_run_mod_handler('number_format', true, $_tmp, '2') : number_format($_tmp, '2')); ?>
</td> 
                        <td class="border"><a href='<?php echo $this->_tpl_vars['SCRIPT_NAME']; ?>
?id=<?php echo $this->_tpl_vars['item']->id; ?>
&cmd=edit' target='_top' title="Edit"><img src="/app_contents/common/images/edit.png" alt="Edit"></a>&nbsp;&nbsp;<a onClick='return doConfirm(PROMPT_DELETE_CONFIRM);' href='<?php echo $this->_tpl_vars['SCRIPT_NAME']; ?>
?id=<?php echo $this->_tpl_vars['item']->id; ?>
&cmd=delete' target='_top'><img src="/app_contents/common/images//cross.png" alt="Delete"><a/></td>
                    </tr>
                <?php endforeach; unset($_from); endif; ?>
                </tbody>
            </table>
        </div>
        <div class="viewmore">&nbsp;
<!--            <table width="760">
                <tr>
                    <td class="font3 padding-left10">Total <?php echo $this->_tpl_vars['row_count']; ?>
 magazine(s) found</td>
                    <td class="font3">Showing page <?php echo $this->_tpl_vars['page_no']+1; ?>
 out of <?php echo $this->_tpl_vars['page_count']; ?>
</td>
                    <td class="font3 padding-right10" align="right"><?php if ($this->_tpl_vars['page_no'] != ''): ?><a href='<?php echo $this->_tpl_vars['SCRIPT_NAME']; ?>
?page_no=<?php echo $this->_tpl_vars['page_no']-1; ?>
&cmd=list&magazine_code=<?php echo $this->_tpl_vars['magazine_code']; ?>
'>Previous</a><?php else: ?>Previous<?php endif; ?> | <?php if ($this->_tpl_vars['page_no'] == $this->_tpl_vars['page_count']-1): ?>Next<?php else: ?><a href='<?php echo $this->_tpl_vars['SCRIPT_NAME']; ?>
?page_no=<?php echo $this->_tpl_vars['page_no']+1; ?>
&cmd=list&magazine_code=<?php echo $this->_tpl_vars['magazine_code']; ?>
'>Next</a><?php endif; ?></td>
                </tr>    
            </table>-->
        </div>
    </form>
</body>
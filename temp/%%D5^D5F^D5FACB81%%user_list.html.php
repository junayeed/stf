<?php /* Smarty version 2.6.6, created on 2013-12-13 21:18:43
         compiled from E:/xampp/htdocs/stf/app_contents/user_manager/user_list.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'E:/xampp/htdocs/stf/app_contents/user_manager/user_list.html', 34, false),)), $this); ?>
<html>
<head>
<link rel="stylesheet" href="<?php echo $this->_tpl_vars['SYSTEM_COMMON_CSS_DIR']; ?>
/default.project.css" type="text/css">
<script language="JavaScript" src="<?php echo $this->_tpl_vars['SYSTEM_COMMON_JAVASCRIPT_DIR']; ?>
/messages.js"></script>
<script language="JavaScript" src="<?php echo $this->_tpl_vars['SYSTEM_COMMON_JAVASCRIPT_DIR']; ?>
/common.js"></script>
<script language="JavaScript" src="<?php echo $this->_tpl_vars['SYSTEM_COMMON_JAVASCRIPT_DIR']; ?>
/sorttable.js"></script>

</head>

<body class="whiteBody">
    <form method="post" action="<?php echo $this->_tpl_vars['SCRIPT_NAME']; ?>
?cmd=list" name="userListForm">
        <div class="customer-left-body">
            <table width="760" cellpadding="0" cellspacing="0" border="0">
                <thead>
                    <tr>
                        <td width="8">&nbsp;</td>
                        <td width="144">Name</td>
                        <td width="107">User Name</td>
                        <td width="108">User Status</td>
                        <td width="72">User Type</td>
                        <td width="108">Create Date</td>
                        <td width="143">Last Updated</td>
                        <td width="70">Action</td>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($_from = (array)$this->_tpl_vars['list'])):
    foreach ($_from as $this->_tpl_vars['item']):
?>
                    <tr>
                        <td width="8" class="border">&nbsp;</td>
                        <td width="144" class="border"><?php echo $this->_tpl_vars['item']->first_name; ?>
 <?php echo $this->_tpl_vars['item']->middle_initial; ?>
 <?php echo $this->_tpl_vars['item']->last_name; ?>
</td>
                        <td width="107" class="border"><?php echo $this->_tpl_vars['item']->username; ?>
</td>
                        <td width="108" class="border"><?php echo $this->_tpl_vars['item']->user_status; ?>
</td>
                        <td width="72" class="border"><?php echo $this->_tpl_vars['item']->user_type; ?>
</td>
                        <td width="108" class="border"><?php echo ((is_array($_tmp=$this->_tpl_vars['item']->create_date)) ? $this->_run_mod_handler('date_format', true, $_tmp, '%m-%d-%Y') : smarty_modifier_date_format($_tmp, '%m-%d-%Y')); ?>
</td>
                        <td width="143" class="border"><?php echo ((is_array($_tmp=$this->_tpl_vars['item']->last_updated)) ? $this->_run_mod_handler('date_format', true, $_tmp, '%m-%d-%Y %H:%M:%S') : smarty_modifier_date_format($_tmp, '%m-%d-%Y %H:%M:%S')); ?>
</td>
                        <td width="70" class="border"><a href="<?php echo $this->_tpl_vars['SCRIPT_NAME']; ?>
?id=<?php echo $this->_tpl_vars['item']->uid; ?>
&cmd=edit" target="_parent"><img src="/app_contents/common/images/edit.png" alt="Edit" /></a>&nbsp;&nbsp;&nbsp;<a onClick='return doConfirm(PROMPT_DELETE_CONFIRM);' href='<?php echo $this->_tpl_vars['SCRIPT_NAME']; ?>
?id=<?php echo $this->_tpl_vars['item']->uid; ?>
&cmd=delete' target='_top'><img src="/app_contents/common/images/cross.png" alt="Cross" /></a></td>
                    </tr>
                    <?php endforeach; unset($_from); endif; ?>
                </tbody>
            </table>
        </div>
<!--        <div class="viewmore">
            <table width="760">
                <tr>
                    <td class="font3 padding-left10">Total <?php echo $this->_tpl_vars['row_count']; ?>
 product(s) found</td>
                    <td class="font3">Showing page <?php echo $this->_tpl_vars['page_no']+1; ?>
 out of <?php echo $this->_tpl_vars['page_count']; ?>
</td>
                    <td class="font3 padding-right10" align="right"><?php if ($this->_tpl_vars['page_no'] != ''): ?><a href='<?php echo $this->_tpl_vars['SCRIPT_NAME']; ?>
?page_no=<?php echo $this->_tpl_vars['page_no']-1; ?>
&cmd=list'>Previous</a><?php else: ?>Previous<?php endif; ?> | <?php if ($this->_tpl_vars['page_no'] == $this->_tpl_vars['page_count']-1): ?>Next<?php else: ?><a href='<?php echo $this->_tpl_vars['SCRIPT_NAME']; ?>
?page_no=<?php echo $this->_tpl_vars['page_no']+1; ?>
&cmd=list'>Next</a><?php endif; ?></td>
                </tr>    
            </table>
        </div>-->
    </form>
</body>
</html>
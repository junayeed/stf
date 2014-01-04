<?php /* Smarty version 2.6.6, created on 2013-12-14 02:20:29
         compiled from E:/xampp/htdocs/stf/app_contents/application_manager/user_manager.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'stripslashes', 'E:/xampp/htdocs/stf/app_contents/application_manager/user_manager.html', 65, false),array('modifier', 'date_format', 'E:/xampp/htdocs/stf/app_contents/application_manager/user_manager.html', 114, false),array('function', 'html_options', 'E:/xampp/htdocs/stf/app_contents/application_manager/user_manager.html', 75, false),)), $this); ?>
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
                    <div class="chose-report">  <!-- choose-report class starts here-->
                        <ul>
                            <li>Choose options:</li>
                            <li><a href="/app/user_manager/user_manager.php" <?php if ($this->_tpl_vars['cmd'] == ''): ?>class="fixed"<?php endif; ?>>available users</a></li>
                            <li><a href="/app/user_manager/user_manager.php?cmd=new" <?php if ($this->_tpl_vars['cmd'] == 'new' || $this->_tpl_vars['cmd'] == 'edit'): ?>class="fixed"<?php endif; ?>>add new user</a></li>
                        </ul>
                    </div>    <!-- choose-report class ends here-->
                    <div class="left">    <!-- left class starts here-->
                        <?php if ($this->_tpl_vars['cmd'] == 'new' || $this->_tpl_vars['cmd'] == 'edit'): ?>
                        <input type="hidden" id="cmd" name="cmd" value="add">
                        <div class="left">   <!-- left class starts here-->
                            <table width="760" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td width="470" class="prepand2" colspan="2">
                                        <h3><span>User Details</span></h3>
                                    </td>
                                </tr>
                                <tr><td>&nbsp;</td></tr>
                                <tr>
                                    <td valign="top">
                                        <table border="0">
                                            <tr>
                                                <td align="center">
                                                <?php if ($this->_tpl_vars['file'] != ''): ?>
                                                    <img src="<?php echo $this->_tpl_vars['file']; ?>
?<?php echo time(); ?>
" class="center">
                                                <?php else: ?>
                                                    <img src="/app_contents/common/images/default.png" id="photoImage" width="150" height="150"  class="center">
                                                <?php endif; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><input type="file" name="photo" id="photo" class="W75"></td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td width="76%">
                                        <table width="100%">  <!-- Access Information table starts here-->
                                            <tr style="background-color: #F2F2F2; height: 25px;">
                                                <td colspan="4">
                                                    <span class="span-text" style="padding-left: 5px;">Access Information</span>
                                                </td>
                                            </tr>
                                            <tr><td><div style=" height: 5px;"></div></td></tr>
                                            <tr>
                                                <td>
                                                    <span class="span-text" style="margin-bottom: 5px; ">User Name<span><br class="br"/>
                                                    <input type="text" name="username" id="username" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['username'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
" class="inputbox3 W110" onchange="checkUserName();">
                                                </td>
                                                <td>
                                                    <span class="span-text">Password</span><br class="br"/>
                                                    <input type="password" name="password" id="password" <?php if ($this->_tpl_vars['password']): ?> value="" <?php endif; ?> class="inputbox3 W110">
                                                </td>
                                                <td>
                                                    <span class="span-text">User Status</span><br class="br"/>
                                                    <select name="user_status" id="user_status" class="combo1 W110">
                                                        <option value="">-- Select --</option>
                                                        <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['user_status_list'],'output' => $this->_tpl_vars['user_status_list'],'selected' => $this->_tpl_vars['user_status']), $this);?>

                                                    </select>
                                                </td>
                                                <td>
                                                    <span class="span-text">User Type</span><br class="br"/>
                                                    <select name="user_type" id="user_type" class="combo1 W110">
                                                        <option value="">-- Select --</option>
                                                        <?php echo smarty_function_html_options(array('output' => $this->_tpl_vars['user_type_list'],'values' => $this->_tpl_vars['user_type_list'],'selected' => $this->_tpl_vars['user_type']), $this);?>

                                                    </select>
                                                </td>
                                            </tr>
                                            <tr><td><div style=" height: 15px;"></div></td></tr>
                                        </table>  <!-- Access Information table ends here-->
                                        
                                        <table width="100%">  <!-- Personal Information table starts here-->
                                            <tr style="background-color: #F2F2F2; height: 25px;">
                                                <td colspan="3">
                                                    <span class="span-text" style="padding-left: 5px;">Personal Information</span>
                                                </td>
                                            </tr>
                                            <tr><td><div style=" height: 5px;"></div></td></tr>
                                            <tr>
                                                <td>
                                                    <span class="span-text">First Name</span><br class="br"/>
                                                    <input type="text" name="first_name" id="first_name" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['first_name'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
" class="inputbox3 W150">
                                                </td>
                                                <td>
                                                    <span class="span-text">Middle Name</span><br class="br"/>
                                                    <input type="text" name="middle_name" id="middle_name" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['middle_name'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
" class="inputbox3 W150">
                                                </td>
                                                <td>
                                                    <span class="span-text">Last Name</span><br class="br"/>
                                                    <input type="text" name="last_name" id="last_name" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['last_name'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
" class="inputbox3 W150">
                                                </td>
                                            </tr>
                                            <tr><td><div style=" height: 10px;"></div></td></tr>
<!--                                            <tr>
                                                <td width="33%">
                                                    <span class="span-text">Date of Birth</span><br class="br"/>
                                                    <input type="text" name="dob" id="dob" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['dob'])) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d') : smarty_modifier_date_format($_tmp, '%Y-%m-%d')); ?>
" class="inputbox3 W123" readonly >
                                                    <img name="anchor1" id="anchor1" align=top width=22 height=20 style="cursor:pointer;cursor:hand" 
                                                         alt="" accesskey="" src="/app_contents/common/images/calendar.gif" 
                                                         class="" onClick="thisSelect(document.userManagerForm.dob,'anchor1','yyyy-MM-dd'); return false;">
                                                </td>
                                                <td width="33%">
                                                    <span class="span-text">Gender</span><br class="br"/>
                                                    <select name="gender" id="gender" class="combo1 W150">
                                                        <option value="">-- Select --</option>  
                                                        <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['gender_list'],'output' => $this->_tpl_vars['gender_list'],'selected' => $this->_tpl_vars['gender']), $this);?>

                                                    </select>
                                                </td>
                                                <td width="33%">
                                                    <span class="span-text">Maritial Status</span><br class="br"/>
                                                    <select name="maritial_status" id="maritial_status" class="combo1 W150" >
                                                        <option value="">-- Select --</option>
                                                        <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['maritial_status_list'],'output' => $this->_tpl_vars['maritial_status_list'],'selected' => $this->_tpl_vars['maritial_status']), $this);?>

                                                    </select>
                                                </td>
                                            </tr>-->
                                            <tr><td><div style=" height: 15px;"></div></td></tr>
                                        </table>  <!-- Personal Information table ends here-->
                                        
                                        <table width="100%">  <!-- Contact Information table starts here-->
                                            <tr style="background-color: #F2F2F2; height: 25px;">
                                                <td colspan="3">
                                                    <span class="span-text" style="padding-left: 5px;">Contact Information</span>
                                                </td>
                                            </tr>
                                            <tr><td><div style=" height: 5px;"></div></td></tr>
                                            <tr>
                                                <td width="33%">
                                                    <span class="span-text">Address</span><br class="br"/>
                                                    <textarea id="permanent_address" name="permanent_address" class="W175"><?php echo ((is_array($_tmp=$this->_tpl_vars['permanent_address'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
</textarea>
                                                </td>
                                                <td valign="top">
                                                    <span class="span-text">City</span><br class="br"/>
                                                    <select name="city" id="city" class="combo1 W150">
                                                        <option value="">-- Select --</option>
                                                        <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['city_list'],'output' => $this->_tpl_vars['city_list'],'selected' => $this->_tpl_vars['city']), $this);?>

                                                    </select>
                                                </td>
                                                <td valign="top">
                                                    <span class="span-text">Post Code</span><br class="br"/>
                                                    <input type="text" name="postcode" id="postcode" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['postcode'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
" class="inputbox3 W150" onkeyup="return doUpperCase(this)">
                                                </td>
                                            </tr>
                                            <tr><td><div style=" height: 10px;"></div></td></tr>
                                            <tr>
                                                <td>
                                                    <span class="span-text">Home Phone</span><br class="br"/>
                                                    <input type="text" name="home_phone" id="home_phone" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['home_phone'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
" class="inputbox3 W150">
                                                </td>
                                                <td>
                                                    <span class="span-text">Mobile</span><br class="br"/>
                                                    <input type="text" name="mobile" id="mobile" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['mobile'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
" class="inputbox3 W150">
                                                </td>
                                                <td>
                                                    <span class="span-text">Email</span><br class="br"/>
                                                    <input type="text" name="email" id="email" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['email'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
" class="inputbox3 W150" onchange="checkUserEmail();">
                                                </td>
                                            </tr>
                                        </table>  <!-- contact Information table ends here-->
                                        <table width="100%">
                                            <tr><td><div style=" height: 20px;"></div></td></tr>
                                            <tr>
                                                <td align="right">
                                                    <input type="submit" value="SAVE" name="save" id="save" class="inputbox-blue">
                                                    <input type="button" value="CANCEL" name="cancel" id="cancel" class="inputbox-blue W57" onClick="doClearForm();">
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <?php else: ?>
                        <div class="left">  <!-- left class starts here-->
                            <table width="760" cellpadding="0" cellspacing="0" border="0">
                                <tr valign="top">
                                    <td width="470" class="prepand2">
                                        <h3><span>Available User(s)</span></h3>
                                    </td>
                                    <td width="137" class="font4" align="center">
                                        <a href="javascript:void(0);" onClick="doUserSearch('All', '', 'user_type', '_all');"><span id="user_type_all" class="font4">all</span></span></a> • 
                                        <a href="javascript:void(0);" onClick="doUserSearch('Admin', '', 'user_type', '_admin');"><span id="user_type_admin" class="font4">admin</span></a> • 
                                        <a href="javascript:void(0);" onClick="doUserSearch('Employee', '', 'user_type', '_employee');"><span id="user_type_employee"  class="font4">employee</span></a>
                                    </td>
                                    <td width="27" class="prepand2">
            <!--                        <img src="/app_contents/common/images/line3.gif" alt="Line" style="margin-top: -10px;"/>-->
                                        <h4 class="line3">&nbsp;</h4>
                                    </td>
                                    <td width="109" class="font4" align="right">
                                        <a href="javascript:void(0);" onClick="doUserSearch('', 'All', 'user_status', '_all');"><span id="user_status_all"  class="font4">all</span></a> • 
                                        <a href="javascript:void(0);" onClick="doUserSearch('', 'Active', 'user_status', '_active');"><span id="user_status_active" class="font4">active</span></a> • 
                                        <a href="javascript:void(0);" onclick="doUserSearch('', 'Inactive', 'user_status', '_inactive');"><span id="user_status_inactive" class="font4">inactive</span></span></a>
                                    </td>
                                </tr>
                            </table>
                        </div>  <!-- left class starts here-->      	
                        <iframe id="usersFrame" src="<?php echo $this->_tpl_vars['SCRIPT_NAME']; ?>
?cmd=list&status=Active" height="300px" width="100%" frameborder="0"  onload='resizeIframe(this);'></iframe>
                        <?php endif; ?>
                    </div>  <!-- left class ends here-->
                    <div class="right prepand2">  <!-- right class starts here-->
                        <h3><span>Settings</span></h3>
                        <div class="setting-list">  <!-- setting-list class starts here-->
                            <ul>
                                <li>
                                    <a href="/app/user_manager/user_manager.php"><span>Manage Users</span></a>
                                    <img src="/app_contents/common/images/icon3.gif" alt="Icon" style="float:right; margin-top:2px;" onClick="hideDiv('magazine-block');hideDiv('product-block');showDiv('user-block');" />
                                    <div class="bubble-block" style="display: none;" id="user-block">                        	
                                        <div class="bubble-round"><img src="/app_contents/common/images/round.png" alt="Round" /></div>
                                        <div class="bubble-body">
                                            <img src="/app_contents/common/images/cross.png"  style="margin-left: 222px;" onClick="hideDiv('user-block');"/>
                                            <p>Create new users and amend/delete details of existing users or delete a user. Only users who are given administration rights will be able to add more users, and configure other elements of the system.</p>
                                            <div class="bubble-arrow"><img src="/app_contents/common/images/bobble-arrow.png" alt="Arrow" /></div>
                                        </div>
                                        <div class="bubble-round"><img src="/app_contents/common/images/round2.png" alt="Round" /></div>
                                    </div>
                                </li>
                                <li>
                                    <a href="/app/magazine_manager/magazine_manager.php">Manage Magazines</a>
                                    <img src="/app_contents/common/images/icon3.gif" alt="Help" style="float:right; margin-top:2px; cursor: pointer;" onClick="hideDiv('user-block');hideDiv('product-block');showDiv('magazine-block');" />
                                    <div class="bubble-block" style="display: none; top:14px;" id="magazine-block">                        	
                                        <div class="bubble-round"><img src="/app_contents/common/images/round.png" alt="Round" /></div>
                                        <div class="bubble-body">
                                            <img src="/app_contents/common/images/cross.png"  style="margin-left: 222px;" onClick="hideDiv('magazine-block');"/>
                                            <p>Create new magazines and amend/delete details of existing magazines. Only users who are given administration rights will be able to add more magazines, and configure other elements of the system.</p>
                                            <div class="bubble-arrow"><img src="/app_contents/common/images/bobble-arrow.png" alt="Arrow" /></div>
                                        </div>
                                        <div class="bubble-round"><img src="/app_contents/common/images/round2.png" alt="Round" /></div>
                                    </div>
                                </li>
                                <li>
                                    <a href="/app/product_manager/product_manager.php">Manage Products</a>
                                    <img src="/app_contents/common/images/icon3.gif" alt="Help" style="float:right; margin-top:2px; cursor: pointer;" onClick="hideDiv('user-block');hideDiv('magazine-block');showDiv('product-block');" />
                                    <div class="bubble-block" style="display: none; top:39px;" id="product-block">                        	
                                        <div class="bubble-round"><img src="/app_contents/common/images/round.png" alt="Round" /></div>
                                        <div class="bubble-body">
                                            <img src="/app_contents/common/images/cross.png"  style="margin-left: 222px;" onClick="hideDiv('product-block');"/>
                                            <p>Create new products, both adverts and other services, amend/delete details of existing products. Only users who are given administration rights will be able to add more products, and configure other elements of the system.</p>
                                            <div class="bubble-arrow"><img src="/app_contents/common/images/bobble-arrow.png" alt="Arrow" /></div>
                                        </div>
                                        <div class="bubble-round"><img src="/app_contents/common/images/round2.png" alt="Round" /></div>
                                    </div>
                                </li>
                                <li>
                                    <a href="/app/capsule_token_manager/capsule_token_manager.php">Manage Integration</a>
                                    <img src="/app_contents/common/images/icon3.gif" alt="Help" style="float:right; margin-top:2px; cursor: pointer;" onClick="hideDiv('user-block');hideDiv('magazine-block');hideDiv('product-block');showDiv('integration-block');" />
                                    <div class="bubble-block" style="display: none; top:64px;" id="integration-block">                        	
                                        <div class="bubble-round"><img src="/app_contents/common/images/round.png" alt="Round" /></div>
                                        <div class="bubble-body">
                                            <img src="/app_contents/common/images/cross.png"  style="margin-left: 222px;" onClick="hideDiv('integration-block');"/>
                                            <p>Your authentication token is like a password for your account that can be used by other applications to access your Capsule data. Only share this token with applications or 3rd parties that you trust. Anyone who has your authentication token can access and update your data.</p>
                                            <div class="bubble-arrow"><img src="/app_contents/common/images/bobble-arrow.png" alt="Arrow" /></div>
                                        </div>
                                        <div class="bubble-round"><img src="/app_contents/common/images/round2.png" alt="Round" /></div>
                                    </div>
                                </li>
                            </ul>
                        </div>   <!-- setting-list class starts here-->
                        <div class="right-body prepand4">  <!-- right-body class starts here-->
                            <h3><span>Customers</span></h3>
                        </div> 
                        <div class="customers-body"  style="margin-top:13px;">
                            <ul>
                                <?php if (count($_from = (array)$this->_tpl_vars['customer_list'])):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
                                    <li>
                                        <a href="/app/order_manager/order_manager.php?customer_id=<?php echo $this->_tpl_vars['key']; ?>
&cmd=edit"><?php echo $this->_tpl_vars['item']; ?>
</a>
                                    </li>
                                <?php endforeach; unset($_from); endif; ?>
                            </ul>
                        </div>
                    </div>       <!-- right class ends here-->
                </div>           <!-- body-block ends here-->
            </div>               <!-- wrap class ends here-->
        </div>                   <!-- body-full ends here-->
    </form>
</body>
<?php /* Smarty version 2.6.6, created on 2014-01-04 08:49:32
         compiled from E:/xampp/htdocs/stf/app_contents/standard/user_home/default_header.html */ ?>
    
     <!-- header -->
        <div id="header">
            <!-- logo -->

            <div id="logo">
                <h1 vlaign="center"><a href="" title="UNDP"><img src="/app_contents/local/theme/admin/images/logo.png" alt="UNDP" /></a>
                	 </h1>
                	
            </div>
            <div id="logo">
            	  <h1>SRF Tracking System</h1>
            	  <h2>United Nations Development Programme, Bangladeh </h2>
            </div>
            <!-- end logo -->
            <!-- user -->
                        <ul id="user">
                <li class="first"><a href="">Account</a></li>
               <li class="first"><a href="#"><?php echo $this->_tpl_vars['USER_FIRST']; ?>
 <?php echo $this->_tpl_vars['USER_LAST']; ?>
 (<?php echo $this->_tpl_vars['USER_TYPE']; ?>
)</a></li>

                <li><a href="http://oms.local/app/standard/logout/logut.php">Logout</a></li>
            </ul>
                        <!-- end user -->
            <div id="header-inner">
                
            </div>

        </div>
        <!-- end header -->



<!--
<table width="100%"  border="0" cellpadding="0" cellspacing="0" class="headerBackground">
  <tr>
    <td width="10%"><img src="/app_contents/common/image/000.gif" width="120" height="31"></td>
    <td width="0%"><img src="/app_contents/common/image/001divider.gif" width="3" height="31"></td>
    <td width="79%" background="/app_contents/common/image/001.gif" align="center"> Welcome <?php echo $this->_tpl_vars['USER_FIRST']; ?>
. You are logged in as <?php echo $this->_tpl_vars['USERNAME']; ?>
 (<?php echo $this->_tpl_vars['USER_TYPE']; ?>
)  </td>

    <td width="0%"><img src="/app_contents/common/image/001divider.gif" width="3" height="31"></td>
    <td width="10%" align="center"><a href="/app/standard/logout/logout.php" class="logout"><img src="/app_contents/common/image/logout.gif" width="120" height="31" border="0"></a></td>
  </tr>
</table>

-->
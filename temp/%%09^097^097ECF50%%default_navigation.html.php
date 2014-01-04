<?php /* Smarty version 2.6.6, created on 2014-01-04 08:49:32
         compiled from E:/xampp/htdocs/stf/app_contents/standard/user_home/default_navigation.html */ ?>
                <div id="menu">
                    <h6 id="h-menu-products" class="selected"><a href="#products"><span>Main Menu</span></a></h6>

                    <ul id="menu-products" class="opened">
                        <li>
											       <a href="/app/procurement_plan_manager/procurement_plan_manager.php">Procurement Plan</a>
                        </li>
                    </ul>
                    <h6 id="h-menu-pages" class="selected"><a href="#pages"><span>Procurement Request</span></a></h6>
                    <ul id="menu-pages" class="opened">
                        <li>
                        	   <a href="/app/srf_manager/srf_manager.php">SRF Manager</a>
                        </li>

                    </ul>
                    <h6 id="h-menu-events" class="selected"><a href="#events"><span>Administration</span></a></h6>
                    <ul id="menu-events" class="opened">
                    	   <li>
                    	   	   <a href="/app/user_manager/user_manager.php">User Manager</a>
												 </li>
												 <li>
											       <a href="/app/project_manager/project_manager.php">Project Manager</a>
                        </li>
                        <li>
                        	   <a href="/app/category_manager/category_manager.php">Category Manager</a>
                        </li>
                       
                    </ul>
                   
                </div>
                <div id="date-picker"></div>




<!--hide nevigation bar starts-->
<!--
<table border="0" width="100%">
<tr>
   <td align="right">
   <a href='javascript:void(0)' onclick="hideDiv('sidebar'); showDiv('nosidebar');" title="Hide Sidebar">
   <img id="hideSidebar" src="/app_contents/common/image/table/close.gif" border="0" alt="Hide Sidebar" onmouseover="showImg('hideSidebar', '/dp/app_contents/common/image/table/close_hvr.gif');" onmouseout="showImg('hideSidebar', '/dp/app_contents/common/image/table/close.gif');">
   </a>
   </td>
</tr>
</table>
<!--hide nevigation bar ends

<table border="0" width="100%">
<tr>
   <?php if ($this->_tpl_vars['nav_item'] == 'home'): ?>
      <td class="navOn">Home</td>
   <?php else: ?>
      <td class="nav"><a href="/home.php">Home</a></td>
   <?php endif; ?>
</tr>
<tr>
   <td class="nav"><a href="/app/standard/logout/logout.php">Logout</a></td>
</tr>
</table>

<br />

<!--the nevigations for 'System Tools' starts
<table class="navHeader" border="0" width="100%">
<tr>
   <td width="10"><img src="/app_contents/common/image/table/mini_arrowright.gif" id="system_img"></td>
   <td>
   <a href='javascript:void(0)' onclick="toggle('system', 'system_img');">System Tools</a>
   </td>
</tr>
</table>

<div id="system"  style="display:block">
<table border="0" width="100%">
<tr>
   <td>&nbsp;</td>
   <td>
      <table width="100%">
         <tr>
         <?php if ($this->_tpl_vars['nav_item'] == 'user'): ?>
            <td class="navOn">User Manager</td>
         <?php else: ?>
            <td class="nav"><a href="/app/user_manager/user_manager.php">User Manager</a></td>
         <?php endif; ?>
         </tr>
         <tr>
         <?php if ($this->_tpl_vars['nav_item'] == 'group'): ?>
            <td class="navOn">Group Manager</td>
         <?php else: ?>
            <td class="nav"><a href="/app/group_manager/group_manager.php">Group Manager</a></td>
         <?php endif; ?>
         </tr>
         <tr>
         <?php if ($this->_tpl_vars['nav_item'] == 'project_manager'): ?>
            <td class="navOn">Project Manager</td>
         <?php else: ?>
            <td class="nav"><a href="/app/project_manager/project_manager.php">Project Manager</a></td>
         <?php endif; ?>
         </tr>
         <tr>
         <?php if ($this->_tpl_vars['nav_item'] == 'procurement_plan'): ?>
            <td class="navOn">Procurement Plan</td>
         <?php else: ?>
            <td class="nav"><a href="/app/procurement_plan_manager/procurement_plan_manager.php">Procurement Plan</a></td>
         <?php endif; ?>
         </tr>
         <tr>
         <?php if ($this->_tpl_vars['nav_item'] == 'srf_manager'): ?>
            <td class="navOn">SRF Manager</td>
         <?php else: ?>
            <td class="nav"><a href="/app/srf_manager/srf_manager.php">SRF Manager</a></td>
         <?php endif; ?>
         </tr>
         <tr>
         <?php if ($this->_tpl_vars['nav_item'] == 'category_manager'): ?>
            <td class="navOn">Category Manager</td>
         <?php else: ?>
            <td class="nav"><a href="/app/category_manager/category_manager.php">Category Manager</a></td>
         <?php endif; ?>
         </tr>
         <!-- <tr>
         <?php if ($this->_tpl_vars['nav_item'] == 'share_market'): ?>
            <td class="navOn">Share Market</td>
         <?php else: ?>
            <td class="nav"><a href="/app/share_market_manager/share_market_manager.php">Share Market</a></td>
         <?php endif; ?>
         </tr>
         <tr>
         <?php if ($this->_tpl_vars['nav_item'] == 'movie_manager'): ?>
            <td class="navOn">Movie Manager</td>
         <?php else: ?>
            <td class="nav"><a href="/app/movie_manager/movie_manager.php">Movie Manager</a></td>
         <?php endif; ?>
         </tr> 
      </table>
   </td>
</tr>
</table>
</div>
<!--the nevigations for 'System Tools' ends

<table class="navHeader" border="0" width="100%">
<tr>
   <td width="10"><img src="/app_contents/common/image/table/mini_arrowright.gif" id="report_img"></td>
   <td>
   <a href='javascript:void(0)' onclick="toggle('report_tool', 'report_img');">Report Tools</a>
   </td>
</tr>
</table>

<div id="report_tool" style="display:block">
<table border="0" width="100%">
<tr>
   <td>&nbsp;</td>
   <td>
      <table width="100%">
         <tr>
         <?php if ($this->_tpl_vars['nav_item'] == 'pdf_rpt'): ?>
            <td class="navOn">PDF Report</td>
         <?php else: ?>
            <td class="nav"><a href="/app/report_manager/report_manager.php">PDF Report</a></td>
         <?php endif; ?>
         </tr>
         <tr>
         <?php if ($this->_tpl_vars['nav_item'] == 'group'): ?>
            <td class="navOn">Graph Report</td>
         <?php else: ?>
            <td class="nav"><a href="/app/report_manager/report_manager.php">Graph Report</a></td>
         <?php endif; ?>
         </tr>
         <tr>
         <?php if ($this->_tpl_vars['nav_item'] == 'user'): ?>
            <td class="navOn">CSV Report</td>
         <?php else: ?>
            <td class="nav"><a href="/app/report_manager/report_manager.php">CSV Report</a></td>
         <?php endif; ?>
         </tr>         
      </table>
   </td>
</tr>
</table>
</div>


<br />
-->
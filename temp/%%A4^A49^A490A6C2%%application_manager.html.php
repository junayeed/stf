<?php /* Smarty version 2.6.6, created on 2014-01-05 13:57:46
         compiled from E:/xampp/htdocs/stf/app_contents/application_manager/application_manager.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'stripslashes', 'E:/xampp/htdocs/stf/app_contents/application_manager/application_manager.html', 68, false),array('modifier', 'date_format', 'E:/xampp/htdocs/stf/app_contents/application_manager/application_manager.html', 343, false),array('function', 'html_options', 'E:/xampp/htdocs/stf/app_contents/application_manager/application_manager.html', 78, false),)), $this); ?>
<!-- Add fancyBox main JS and CSS files -->
<script type="text/javascript" src="/ext/jquery-ui/js/jquery.fancybox.js?v=2.1.4"></script>
<link rel="stylesheet" type="text/css" href="/ext/jquery-ui/css/jquery.fancybox.css?v=2.1.4" media="screen" />
<link rel="stylesheet" type="text/css" href="/ext/jquery-ui/css/ui-lightness/jquery.ui.all.css"  media="screen" />
<link rel="stylesheet" href="/ext/jquery-ui/css/jquery-ui.css" />

<!-- Add Uploadify main JS and CSS files -->
<script src="/ext/uploadify/jquery.uploadify.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="/ext/uploadify/uploadify.css">

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
                    <div class="left">    <!-- left class starts here-->
                        <?php if ($this->_tpl_vars['application_status'] == 'Not Submitted' || $this->_tpl_vars['application_status'] == ''): ?>
                        
                        <?php if ($this->_tpl_vars['cmd'] == 'new' || $this->_tpl_vars['cmd'] == 'edit'): ?>
                        <input type="hidden" id="cmd" name="cmd" value="add">
                        <div class="left">   <!-- left class starts here-->
                            <?php echo $this->_tpl_vars['message']; ?>

                            <table width="850" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td width="470" class="prepand2" colspan="2">
                                        <h3><span>Application Details</span></h3>
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top" align="center">
                                        <table border="0">
                                            <tr>
                                                <td align="left">
                                                <?php if ($this->_tpl_vars['photo_id'] != 0): ?>
                                                    <img src="<?php echo $this->_tpl_vars['file']; ?>
?<?php echo time(); ?>
" width="150" height="150">
                                                <?php else: ?>
                                                    <img src="/app_contents/common/images/default.png" id="photoImage" width="150" height="150">
                                                <?php endif; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><input type="file" name="photo" id="photo" class="W75"></td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td width="80%">
                                        <!-- Personal Information starts here -->
                                        <div class="main-content">
                                            <h4 class="header">Personal Information</h4>
                                            <div class="table-content">
                                                <table width="100%">
                                                    <tr>
                                                        <td width="33%">
                                                            <span class="span-text">First Name</span><br class="br"/>
                                                            <input type="text" name="first_name" id="first_name" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['first_name'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
" class="inputbox3 W150">
                                                        </td>
                                                        <td width="33%">
                                                            <span class="span-text">Last Name</span><br class="br"/>
                                                            <input type="text" name="last_name" id="last_name" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['last_name'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
" class="inputbox3 W150">
                                                        </td>
                                                        <td width="33%">
                                                            <span class="span-text">Gender</span><br class="br"/>
                                                            <select id="gender" name="gender" class="combo1 W150">
                                                                <option value="">Select Gender</option>
                                                                <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['gender_list'],'output' => $this->_tpl_vars['gender_list'],'selected' => $this->_tpl_vars['gender']), $this);?>

                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr><td><div style=" height: 5px;"></div></td></tr>
                                                    <tr>
                                                        <td>
                                                            <span class="span-text">Present Address</span><br class="br"/>
                                                            <textarea id="present_address" name="present_address" class="textarea W185 H60"><?php echo ((is_array($_tmp=$this->_tpl_vars['present_address'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
</textarea>
                                                        </td>
                                                        <td valign="top">
                                                            <span class="span-text">Present Phone</span><br class="br"/>
                                                            <input type="text" name="present_phone" id="present_phone" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['present_phone'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
" 
                                                                   class="inputbox3 W150" onkeypress="return isNumberKey(event)">
                                                        </td>
                                                        <td valign="top">
                                                            <span class="span-text">Email</span><br class="br"/>
                                                            <input type="text" name="email" id="email" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['email'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
" class="inputbox3 W185" onChange="isUserEmailExists();">
                                                        </td>
                                                    </tr>
                                                    <tr><td><div style=" height: 5px;"></div></td></tr>
                                                    <tr>
                                                        <td>
                                                            <span class="span-text">Permanent Address</span><br class="br"/>
                                                            <textarea id="permanent_address" name="permanent_address" class="textarea W185 H60"><?php echo ((is_array($_tmp=$this->_tpl_vars['permanent_address'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
</textarea>
                                                            <div id="copy-address">
                                                                <img src="/app_contents/common/images/copy26.png" width="20" onClick="copyAddress('present_address', 'permanent_address');">
                                                            </div>
                                                        </td>
                                                        <td valign="top">
                                                            <span class="span-text">Permanent Phone</span><br class="br"/>
                                                            <input type="text" name="permanent_phone" id="permanent_phone" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['permanent_phone'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
" 
                                                                   class="inputbox3 W150" onkeypress="return isNumberKey(event)">
                                                        </td>
                                                        <td valign="top">
                                                            <span class="span-text">Received Grant Before</span><br class="br"/>
                                                            <select name="received_grant" id="received_grant" class="combo1 W150">
                                                                <option value=""></option>
                                                                <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['received_grant_list'],'output' => $this->_tpl_vars['received_grant_list'],'selected' => $this->_tpl_vars['received_grant']), $this);?>

                                                            </select>
                                                        </td>
                                                    </tr>
                                                </table>  
                                            </div> 
                                        </div>
                                        <!-- Personal Information table ends here-->
                                        
                                        <!-- Guardian Information table starts here-->
                                        <div class="main-content">
                                            <h4 class="header">Guardian Information</h4>
                                            <div class="table-content">
                                                <table width="100%">
                                                    <tr>
                                                        <td width="33%">
                                                            <span class="span-text">Name</span><br class="br"/>
                                                            <input type="text" name="guardian_name" id="guardian_name" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['guardian_name'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
" class="inputbox3 W150">
                                                        </td>
                                                        <td valign="top">
                                                            <span class="span-text">Occupation</span><br class="br"/>
                                                            <input type="text" name="guardian_occupation" id="guardian_occupation" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['guardian_occupation'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
" class="inputbox3 W150">
                                                        </td>
                                                        <td valign="top">
                                                            <span class="span-text">Annual Income</span><br class="br"/>
                                                            <input type="text" name="guardian_income" id="guardian_income" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['guardian_income'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
" 
                                                                   class="inputbox3 W150" onkeypress="return isNumberKey(event)">
                                                        </td>
                                                    </tr>
                                                    <tr><td><div style=" height: 10px;"></div></td></tr>
                                                    <tr>
                                                        <td>
                                                            <span class="span-text">Income Tax Return/Income Statement</span><br class="br"/>
                                                            <input type="file" name="guardian_income_tax" id="guardian_income_tax" class="W175">
                                                        </td>
                                                        <td colspan="2">
                                                            <?php if ($this->_tpl_vars['guardian_doc_id']): ?><a href="<?php echo $this->_tpl_vars['guardian_file']; ?>
"><img src="/app_contents/common/images/view22.png" class="padding-left10"></a><?php endif; ?>
                                                        </td>
                                                    </tr>
                                                </table>  
                                            </div>
                                        </div>
                                        <!-- Guardian Information table ends here-->
                                        
                                        <!-- Academic Qualifications table starts here-->
                                        <div class="main-content">
                                            <h4 class="header">Academic Qualifications</h4>
                                            <div class="table-content">
                                                <table width="100%"> 
                                                    <tr>
                                                        <td colspan="3">
                                                            <table width="100%" id="academic_qualifications" border="0">
                                                                <thead>
                                                                    <tr>
                                                                        <td width="4">&nbsp;</td>
                                                                        <td width="47"><span>Exam/Degree</span></td>
                                                                        <td width="47"><span>Attachment Name</span></td>
                                                                        <td width="47"><span> </span></td>
                                                                        <td width="47"><span>Attachment</span></td>
                                                                        <td width="50"><span>Action</span></td>
                                                                    </tr>
                                                                </thead>
                                                                <tbody></tbody>
                                                            </table>
                                                            <br />
                                                            <a href="javascript:void(0);"  onClick="addNewRow();">
                                                                <img src="/app_contents/common/images/list1_add_new_22.png" alt="Add New" /> 
                                                                <span class="span-text"><u>Add Another Academic Qualifications</u></span>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                        <!-- Academic Qualification table ends here-->
                                        
                                        <!-- University Information table starts here-->
                                        <div class="main-content">
                                            <h4 class="header">University Information (where the applicant has been enrolled)</h4>
                                            <div class="table-content">
                                                <table width="100%">
                                                    <tr>
                                                        <td>
                                                            <span class="span-text">Country</span><br class="br"/>
                                                            <select id="country" name="country" class="combo1 W150" onChange="toggleOptions();">
                                                                <option value="">Select Country</option>
                                                                <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['country_list'],'selected' => $this->_tpl_vars['country']), $this);?>

                                                            </select>    
                                                        </td>
                                                        <td colspan="2">
                                                            <span class="span-text">Name of the university/education institution</span><br class="br"/>
                                                            <input type="text" name="university_name" id="university_name" value="<?php echo $this->_tpl_vars['university_name']; ?>
"class="inputbox3 W300">
                                                        </td>
                                                    </tr>
                                                    <tr><td><div style=" height: 10px;"></div></td></tr>
                                                    <tr>
                                                        <td colspan="3">
                                                            <span class="span-text">Name of the institution's contact person along with address, telephone number(s) and email</span><br class="br"/>
                                                            <textarea name="university_contact" id="university_contact" class="textarea W525"><?php echo $this->_tpl_vars['university_contact']; ?>
</textarea>
                                                        </td>
                                                    </tr>
                                                    <tr><td><div style=" height: 10px;"></div></td></tr>
                                                    <tr>
                                                        <td colspan="3">
                                                            <span class="span-text">Brief description of the subject(s) being studied</span><br class="br"/>
                                                            <textarea name="subject_desc" id="subject_desc" class="textarea W525 H90"><?php echo $this->_tpl_vars['subject_desc']; ?>
</textarea>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                        <!-- University Information table ends here-->
                                        
                                        <!-- Acceptance Letter and Fellowship letter start here -->
                                        <div class="main-content">
                                            <h4 class="header">Acceptance Letter and Followship/Scholarship Award issue by the enrolling educational institute (to be enclosed)</h4>
                                            <div class="table-content">
                                            <table  width="100%">
                                                    <tr>
                                                        <td width="34%"><span class="span-text">Acceptance Letter: </span> </td>
                                                        <td width="34%"><input type="file" name="acceptance_letter" id="acceptance_letter" class="W175"></td>
                                                        <td>
                                                            <?php if ($this->_tpl_vars['acceptance_doc_id'] > 0): ?>
                                                                <a href="<?php echo $this->_tpl_vars['acceptance_letter_file']; ?>
"><img src="/app_contents/common/images/view22.png"></a>
                                                            <?php endif; ?>
                                                        </td>
                                                     </tr>
                                                     <tr>
                                                         <td><span class="span-text">Followship/Scholarship Award Letter: </span></td>
                                                         <td><input type="file" name="scholarship_letter" id="scholarship_letter" class="W175"></td>
                                                         <td>
                                                             <?php if ($this->_tpl_vars['scholarship_doc_id'] > 0): ?>
                                                                <a href="<?php echo $this->_tpl_vars['scholarship_letter_file']; ?>
"><img src="/app_contents/common/images/view22.png"></a>
                                                             <?php endif; ?>
                                                         </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                        <!-- Acceptance Letter and Fellowship letter END here -->
                                        
                                        <!-- Certification START here -->
                                        <div class="main-content">
                                            <h4 class="header H35">Certification from the enrolling educational institute to the effect that no travel expense has been not or would be reimbursed to the applicant (to be enclosed):</h4>
                                            <div class="table-content">
                                                <table  width="100%">
                                                    <tr>
                                                        <td width="45%">
                                                            <span class="span-text"> </span><br class="br"/>
                                                            <input type="file" name="enroll_certification" id="enroll_certification" class="W175">
                                                        </td>
                                                        <td>
                                                            <?php if ($this->_tpl_vars['enroll_doc_id'] > 0): ?>
                                                                <a href="<?php echo $this->_tpl_vars['enroll_certification_file']; ?>
"><img src="/app_contents/common/images/view22.png" class="padding-left10"></a>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                        <!-- Certification END here -->
                                        
                                        <!-- I-20 STARTS here-->
                                        <div class="main-content" id="i20">
                                            <h4 class="header">Copy of the duly signed I-20 form the enrolling educational institute: </h4>
                                            <div class="table-content">
                                                <table width="100%">
                                                    <tr>
                                                        <td  width="45%">
                                                            <span class="span-text"></span><br class="br"/>
                                                            <input type="file" name="i20" id="i20" class="W300">
                                                        </td>
                                                        <td>
                                                            <?php if (i20_doc_id > 0): ?>
                                                            <a href="<?php echo $this->_tpl_vars['i20_file']; ?>
"><img src="/app_contents/common/images/view22.png" class="padding-left10"></a>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                </table>  
                                            </div>
                                        </div>
                                        <!-- I-20 ENDS here-->
                                        
                                        <!-- Others Qualifications STARTS here-->
                                        <div class="main-content" id="others-qualifications">
                                            <h4 class="header">Others (optional): </h4>
                                            <div class="table-content">
                                                <table width="100%">
                                                    <tr>
                                                        <td>
                                                            <span class="span-text">TOFEL</span><br class="br"/>
                                                            <input type="text" name="tofel" id="tofel" value="<?php echo $this->_tpl_vars['tofel']; ?>
" class="inputbox3 W60">
                                                        </td>
                                                        <td>
                                                            <span class="span-text">IELTS</span><br class="br"/>
                                                            <input type="text" name="ielts" id="ielts" value="<?php echo $this->_tpl_vars['ielts']; ?>
" class="inputbox3 W60">
                                                        </td>
                                                        <td>
                                                            <span class="span-text">SAT</span><br class="br"/>
                                                            <input type="text" name="sat" id="sat" value="<?php echo $this->_tpl_vars['sat']; ?>
" class="inputbox3 W60">
                                                        </td>
                                                        <td>
                                                            <span class="span-text">GRE</span><br class="br"/>
                                                            <input type="text" name="gre" id="gre" value="<?php echo $this->_tpl_vars['gre']; ?>
" class="inputbox3 W60">
                                                        </td>
                                                        <td>
                                                            <span class="span-text">GMAT</span><br class="br"/>
                                                            <input type="text" name="gmat" id="gmat" value="<?php echo $this->_tpl_vars['gmat']; ?>
" class="inputbox3 W60">
                                                        </td>
                                                    </tr>
                                                </table>  
                                            </div>
                                        </div>
                                        <!-- Others Qualifications ENDS here-->
                                        
                                        <!-- Air Fare Information table starts here-->
                                        <div class="main-content">
                                            <h4 class="header">Air Fare Details (Cost of one way ticket in BDT and USD)</h4>
                                            <div class="table-content">
                                                <table width="100%">  
                                                    <tr>
                                                        <td width="33%">
                                                            <span class="span-text">Ticket Number</span><br class="br"/>
                                                            <input type="text" name="ticket_number" id="ticket_number" value="<?php echo $this->_tpl_vars['ticket_number']; ?>
" class="inputbox3 W150">
                                                        </td>
                                                        <td>
                                                            <span class="span-text">Date of Ticket</span><br class="br"/>
                                                            <input type="text" name="date_ticket" id="date_ticket" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['date_ticket'])) ? $this->_run_mod_handler('date_format', true, $_tmp, '%d/%m/%Y') : smarty_modifier_date_format($_tmp, '%d/%m/%Y')); ?>
" class="inputbox3 W123">
                                                            <img name="anchor1" id="anchor1" align=top width=22 height=20 
                                                                 style="cursor:pointer;cursor:hand;margin-top: 2px;" src="/app_contents/common/images/calendar.gif" 
                                                                 onClick="thisSelect(document.userManagerForm.date_ticket,'anchor1','dd/MM/yyyy'); return false;">
                                                        </td>
                                                    </tr>
                                                    <tr><td colspan="3"><div style=" height: 5px;"></div></td></tr>
                                                    <tr>
                                                        <td width="33%">
                                                            <span class="span-text">Ticket Fare (BDT)</span><br class="br"/>
                                                            <input type="text" name="ticket_fare" id="ticket_fare" onkeyup="calculateFareBDT()" 
                                                                   value="<?php echo $this->_tpl_vars['ticket_fare']; ?>
" class="inputbox3 W150" onkeypress="return isNumberKey(event);">
                                                        </td>
                                                        <td width="33%">
                                                            <span class="span-text">Tax (BDT)</span><br class="br"/>
                                                            <input type="text" name="tax" id="tax" value="<?php echo $this->_tpl_vars['tax']; ?>
" onkeyup="calculateFareBDT()" 
                                                                   class="inputbox3 W150" onkeypress="return isNumberKey(event);">
                                                        </td>
                                                        <td width="33%">
                                                            <span class="span-text">Total (BDT)</span><br class="br"/>
                                                            <input type="text" name="total" id="total" value="<?php echo $this->_tpl_vars['total']; ?>
" class="inputbox3 W150 DISABLE" readonly>
                                                        </td>
                                                    </tr>
                                                    <tr><td colspan="3"><div style=" height: 5px;"></div></td></tr>
                                                    <tr>
                                                        <td width="33%">
                                                            <span class="span-text">Ticket Fare (USD)</span><br class="br"/>
                                                            <input type="text" name="ticket_fare_usd" id="ticket_fare_usd" onkeyup="calculateFareUSD()" 
                                                                   value="<?php echo $this->_tpl_vars['ticket_fare_usd']; ?>
" class="inputbox3 W150" onkeypress="return isNumberKey(event)">
                                                        </td>
                                                        <td width="33%">
                                                            <span class="span-text">Tax (USD)</span><br class="br"/>
                                                            <input type="text" name="tax_usd" id="tax_usd" onkeyup="calculateFareUSD()" value="<?php echo $this->_tpl_vars['tax_usd']; ?>
" 
                                                                   class="inputbox3 W150" onkeypress="return isNumberKey(event)">
                                                        </td>
                                                        <td width="33%">
                                                            <span class="span-text">Total (USD)</span><br class="br"/>
                                                            <input type="text" name="total_usd" id="total_usd" value="<?php echo $this->_tpl_vars['total_usd']; ?>
" class="inputbox3 W150 DISABLE" readonly>
                                                        </td>
                                                    </tr>
                                                    <tr><td colspan="3"><div style=" height: 5px;"></div></td></tr>
                                                    <tr>
                                                        <td width="33%">
                                                            <span class="span-text">Attachement of Ticket</span><br class="br"/>
                                                            <input type="file" name="ticket_doc" id="ticket_doc" class="W185">
                                                        </td>
                                                        <td colspan="2">
                                                            <?php if ($this->_tpl_vars['ticket_doc_id']): ?>
                                                                <a href="<?php echo $this->_tpl_vars['ticket_file']; ?>
"><img src="/app_contents/common/images/view22.png" class="padding-left10"></a>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                        <div id="buttons-div" class="button-div">
                                            <input type="button" value="SUBMIT" name="save" id="save" class="inputbox-blue margin-right50" onClick="doApplicationSubmit();">  
                                            <input type="submit" value="SAVE"   name="save" id="save" class="inputbox-blue">
                                            <input type="button" value="CANCEL" name="cancel" id="cancel" class="inputbox-blue W57" onClick="doClearForm();">
                                            <input type="hidden" name="submitted" value="0">
                                        </div>
                                        <table width="100%">
                                            <tr><td><div style=" height: 20px;"></div></td></tr>
                                            <tr>
                                                <td align="right">
                                                    
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <?php endif; ?>
                        
                        <?php else: ?>
                            <?php if ($this->_tpl_vars['application_status'] == 'Accepted'): ?>
                                <div class="success">Congratulations!!! Your application has accepted.</div>
                            <?php elseif ($this->_tpl_vars['application_status'] == 'Rejected'): ?>
                                <div class="error">Sorry. Your application has been rejected.</div>
                            <?php else: ?>
                                <div class="info">You have already submitted your application. Please wait for the scholarship result. You will be notified the result via email. </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>  <!-- left class ends here-->
                    
                </div>           <!-- body-block ends here-->
            </div>               <!-- wrap class ends here-->
        </div>                   <!-- body-full ends here-->
        
    </form>
    <script language="JavaScript">
    <?php if (count($_from = (array)$this->_tpl_vars['academic_qualifications'])):
    foreach ($_from as $this->_tpl_vars['item']):
?>
        addNewRow(0);
        populateAcademicDetails("<?php echo $this->_tpl_vars['item']->id; ?>
", "<?php echo $this->_tpl_vars['item']->uid; ?>
", "<?php echo $this->_tpl_vars['item']->degree; ?>
", "<?php echo $this->_tpl_vars['item']->attachmentname; ?>
","<?php echo $this->_tpl_vars['item']->file_location; ?>
", "<?php echo $this->_tpl_vars['item']->doc_id; ?>
");
    <?php endforeach; unset($_from); endif; ?>
    //getMonthList();
    //getYearList();
    calculateFareBDT();
    calculateFareUSD();
    
    toggleOptions();
</script>   
</body>
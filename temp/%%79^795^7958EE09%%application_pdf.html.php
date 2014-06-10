<?php /* Smarty version 2.6.6, created on 2014-06-08 13:03:22
         compiled from E:/xampp/htdocs/stf/app_contents/application_manager/application_pdf.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'number_format', 'E:/xampp/htdocs/stf/app_contents/application_manager/application_pdf.html', 202, false),)), $this); ?>
<style>
    body{
        font-family:arial;
        font-size: 13.5px;
        text-align:left;
    }
</style>    

<body>
                    <table  border="0" width="100%" cellspacing="6">
                        <tr>
                            <td>
                                <label id="">Application ID:</label> <b><?php echo $this->_tpl_vars['app_id']; ?>
</b><br><br>
                            </td>
                            <td colspan="2" align="right"><img src="<?php echo $this->_tpl_vars['doc_root']; ?>
/<?php echo $this->_tpl_vars['file']; ?>
?<?php echo time(); ?>
" width="150" height="150"></td>
                        </tr>
                        <tr>
                            <td colspan="3" align="center">
                                <p style="font-size:18px;"><b>Application Form</b></p>
                                <b>Reimbursement of Expenses against one way air travel from the Bangladesh Sweden Trust Fund.</b><br>(Academic Session: <?php echo $this->_tpl_vars['session_year']; ?>
)
                            </td>    
                        </tr>
                        <tr>
                            <td width="29%"></td>
                            <td width="1%">&nbsp;</td>
                            <td width="70%"></td>
                        </tr>
                        <tr>
                            <td width="29%">Name</td>
                            <td width="1%">:</td>
                            <td width="70%"><?php echo $this->_tpl_vars['first_name']; ?>
 <?php echo $this->_tpl_vars['last_name']; ?>
</td>
                        </tr>
                        <tr>
                            <td>Gender</td>
                             <td>:</td>
                            <td><?php echo $this->_tpl_vars['gender']; ?>
</td>
                        </tr>
                        <tr>
                            <td>Guardian Name</td>
                             <td>:</td>
                            <td><?php echo $this->_tpl_vars['guardian_name']; ?>
</td>
                        </tr>
                        <tr>
                            <td>Guardian Occupation</td>
                             <td>:</td>
                            <td><?php echo $this->_tpl_vars['guardian_occupation']; ?>
</td>
                        </tr>
                        <tr>
                            <td>Guardian Income</td>
                             <td>:</td>
                            <td><?php echo $this->_tpl_vars['guardian_income']; ?>
</td>
                        </tr>
                        <tr>
                            <td width="29%"></td>
                            <td width="1%">&nbsp;</td>
                            <td width="70%"></td>
                        </tr>
                        <tr>
                            <td style="background-color:#CCCCFF;" colspan="3">Address of the Applicant in Bangladesh</td>
                        </tr>
                        <tr>
                            <td>Present Address </td>
                             <td>:</td>
                            <td><?php echo $this->_tpl_vars['present_address']; ?>
</td>
                        </tr>
                        <tr>
                            <td>Present Phone</td>
                             <td>:</td>
                            <td><?php echo $this->_tpl_vars['present_phone']; ?>
</td>
                        </tr>
                        <tr>
                            <td>Permanent Address</td>
                             <td>:</td>
                            <td><?php echo $this->_tpl_vars['permanent_address']; ?>
</td>
                        </tr>
                        <tr>
                            <td>Cell Phone</td>
                             <td>:</td>
                            <td><?php echo $this->_tpl_vars['cell_phone']; ?>
</td>
                        </tr>
                        <tr>
                            <td>Email</td>
                             <td>:</td>
                            <td><?php echo $this->_tpl_vars['email']; ?>
</td>
                        </tr>
                        <tr>
                            <td>Education Qualification</td>
                             <td>:</td>
                            <td><b>Attached</b></td>
                        </tr>
                        <tr>
                            <td width="29%"></td>
                            <td width="1%">&nbsp;</td>
                            <td width="70%"></td>
                        </tr>
                        <tr>
                            <td style="background-color:#CCCCFF;" colspan="3">University Information (where the applicant has been enrolled)</td>
                        </tr>
                        <tr>
                            <td>Country</td>
                             <td>:</td>
                            <td><?php echo $this->_tpl_vars['country_name']; ?>
</td>
                        </tr>
                        <tr>
                            <td>University Name</td>
                             <td>:</td>
                            <td><?php echo $this->_tpl_vars['university_name']; ?>
</td>
                        </tr>
                        <tr>
                            <td>Institution's contact person's details</td>
                             <td>:</td>
                            <td><?php echo $this->_tpl_vars['university_contact']; ?>
</td>
                        </tr>
                        <tr>
                            <td>Field of Study including Major</td>
                             <td>:</td>
                            <td><?php echo $this->_tpl_vars['subject_desc']; ?>
</td>
                        </tr>
                        <tr>
                            <td>Acceptance Letter</td>
                             <td>:</td>
                            <td><b><?php if ($this->_tpl_vars['acceptance_doc_id'] > 0): ?>Attached<?php else: ?>No Attachment<?php endif; ?></b></td>
                        </tr>
                        <tr>
                            <td>Followship/Scholarship Award Letter</td>
                             <td>:</td>
                            <td><b><?php if ($this->_tpl_vars['scholarship_doc_id'] > 0): ?>Attached<?php else: ?>No Attachment<?php endif; ?></b></td>
                        </tr>
                        <tr>
                            <td >Certification from the enrolling educational institute to the effect that no travel expense has been not or would be reimbursed to the applicant (to be enclosed): </td>
                             <td>:</td>
                            <td><b><?php if ($this->_tpl_vars['enroll_doc_id'] > 0): ?>Attached<?php else: ?>No Attachment<?php endif; ?></b></td>
                        </tr>
                        <?php if ($this->_tpl_vars['country'] == 'US'): ?>
                        <tr>
                            <td>I-20</td>
                             <td>:</td>
                            <td><b><?php if ($this->_tpl_vars['i20_doc_id'] > 0): ?>Attached<?php else: ?>No Attachment<?php endif; ?></b></td>
                        </tr>
                        <?php endif; ?>
                        <?php if ($this->_tpl_vars['tofel']): ?>
                        <tr>
                            <td>TOFEL</td>
                             <td>:</td>
                            <td><?php echo $this->_tpl_vars['tofel']; ?>
 (<b><?php if ($this->_tpl_vars['tofel_doc_id'] > 0): ?>Attached<?php else: ?>No Attachment<?php endif; ?></b>)</td>
                        </tr>
                        <?php endif; ?>
                        <?php if ($this->_tpl_vars['ielts']): ?>
                        <tr>
                            <td>IELTS</td>
                             <td>:</td>
                            <td><?php echo $this->_tpl_vars['ielts']; ?>
 (<b><?php if ($this->_tpl_vars['ielts_doc_id'] > 0): ?>Attached<?php else: ?>No Attachment<?php endif; ?></b>)</td>
                        </tr>
                        <?php endif; ?>
                        <?php if ($this->_tpl_vars['sat']): ?>
                        <tr>
                            <td>SAT</td>
                             <td>:</td>
                            <td><?php echo $this->_tpl_vars['sat']; ?>
 (<b><?php if ($this->_tpl_vars['sat_doc_id'] > 0): ?>Attached<?php else: ?>No Attachment<?php endif; ?></b>)</td>
                        </tr>
                        <?php endif; ?>
                        <?php if ($this->_tpl_vars['gre']): ?>
                        <tr>
                            <td>GRE</td>
                             <td>:</td>
                            <td><?php echo $this->_tpl_vars['gre']; ?>
 (<b><?php if ($this->_tpl_vars['gre_doc_id'] > 0): ?>Attached<?php else: ?>No Attachment<?php endif; ?></b>)</td>
                        </tr>
                        <?php endif; ?>
                        <?php if ($this->_tpl_vars['gmat']): ?>
                        <tr>
                            <td>GMAT</td>
                             <td>:</td>
                            <td><?php echo $this->_tpl_vars['gmat']; ?>
 (<b><?php if ($this->_tpl_vars['gmat_doc_id'] > 0): ?>Attached<?php else: ?>No Attachment<?php endif; ?></b>)</td>
                        </tr>
                        <?php endif; ?>
                        <tr>
                            <td width="29%"></td>
                            <td width="1%">&nbsp;</td>
                            <td width="70%"></td>
                        </tr>
                         <tr>
                             <td style="background-color:#CCCCFF;" colspan="3">Cost of one-way ticket (in US and BD Taka) </td>
                        </tr>
                        <tr>
                            <td>Ticket Number </td>
                             <td>:</td>
                            <td><?php echo $this->_tpl_vars['ticket_number']; ?>
</td>
                        </tr>
                        <tr>
                            <td>Ticket Date </td>
                             <td>:</td>
                            <td><?php echo $this->_tpl_vars['date_ticket']; ?>
</td>
                        </tr>
                        <tr>
                            <td>Destination Airport</td>
                             <td>:</td>
                            <td><?php echo $this->_tpl_vars['destination_airport']; ?>
</td>
                        </tr>
                        <tr>
                            <td> Total (Ticket Fare + TAX)</td>
                             <td>:</td>
                            <td>$ <?php echo ((is_array($_tmp=$this->_tpl_vars['ticket_fare_usd'])) ? $this->_run_mod_handler('number_format', true, $_tmp, '2') : number_format($_tmp, '2')); ?>
+$ <?php echo ((is_array($_tmp=$this->_tpl_vars['tax_usd'])) ? $this->_run_mod_handler('number_format', true, $_tmp, '2') : number_format($_tmp, '2')); ?>
 = $ <?php echo ((is_array($_tmp=$this->_tpl_vars['total_usd'])) ? $this->_run_mod_handler('number_format', true, $_tmp, '2') : number_format($_tmp, '2')); ?>
</td>    
                        </tr>
                        <tr>
                            <td> Total (Ticket Fare + TAX)</td>
                             <td>:</td>
                            <td> BDT <?php echo ((is_array($_tmp=$this->_tpl_vars['ticket_fare'])) ? $this->_run_mod_handler('number_format', true, $_tmp, '2') : number_format($_tmp, '2')); ?>
+BDT <?php echo ((is_array($_tmp=$this->_tpl_vars['tax'])) ? $this->_run_mod_handler('number_format', true, $_tmp, '2') : number_format($_tmp, '2')); ?>
 = BDT <?php echo ((is_array($_tmp=$this->_tpl_vars['total'])) ? $this->_run_mod_handler('number_format', true, $_tmp, '2') : number_format($_tmp, '2')); ?>
</td>    
                        </tr>
                        <tr>
                            <td>Ticket Document </td>
                             <td>:</td>
                            <td><b><?php if ($this->_tpl_vars['ticket_doc_id'] > 0): ?>Attached<?php else: ?>No Attachment<?php endif; ?></b></td>
                        </tr>
                        <tr style="height: 80px;">
                            <td></td>
                            <td> </td>
                             <td><br/><br/><br/><br/></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td align="center" style="border-top: 1px solid black;">Applicant's Signature with Date</td>
                        </tr>
                       
                    </table>
</body>          
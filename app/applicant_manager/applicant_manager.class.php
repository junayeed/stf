<?php

/**
 * File: applicant_manager.class.php
 *
 * @copyright {@link www.softbizsoltion.com }
 * @author  junayeed@gmail.com
 */

/**
 * The applicantManager application class
 */

class applicantManagerApp extends DefaultApplication
{
   /**
   * Constructor
   * @return true
   */

   function run()
   {
      $cmd = getUserField('cmd');  
      
      switch ($cmd)
      {
           case 'edit'               : $screen = $this->showEditor($msg);                 break;
           case 'new'                : $screen = $this->showNewEditor($msg);              break;
           case 'add'                : $screen = $this->saveRecord();                     break;
           case 'delete'             : $screen = $this->deleteRecord();                   break;
           case 'list'               : $screen = $this->showList();                       break;
           case 'excel'              : $screen = $this->showList();                       break;
           case 'checkuser'          : $screen = $this->checkDuplicateUser();             break;
           case 'checkemail'         : $screen = $this->checkDuplicateEmail();            break;
           case 'deletedoc'          : $screen = $this->deleteAcademicDoc();              break;
           case 'viewapp'            : $screen = $this->viewApplication();                break;
           case 'acceptall'          : $screen = $this->acceptAll();                      break;
           case 'rejectall'          : $screen = $this->rejectAll();                      break;
           case 'acceptApplication'  : $screen = $this->acceptApplication();              break;
           case 'saveRemarks'        : $screen = $this->saveRemarks();                    break;
           case 'rejectApplication'  : $screen = $this->rejectApplication();              break;
           case 'sendmail'           : $screen = $this->sendMailToAcceptedApplicants();   break;
           
           default           : $screen = $this->showEditor($msg);
      }

      // Set the current navigation item
      $this->setNavigation('user');
      
      if ($cmd == 'checkuser' || $cmd == 'checkemail' || $cmd=='viewapp' || $cmd=='acceptall' || $cmd == 'sendmail' ||
          $cmd == 'rejectall' || $cmd=='acceptApplication' || $cmd=='rejectApplication' || $cmd=='saveRemarks')
      {
          return;
      }

      if ($cmd == 'list' || $cmd == 'excel')
      {
         echo $screen;
      }
      else
      {
         echo $this->displayScreen($screen);
      }

      return true;
   }
   
    function sendMailToAcceptedApplicants()
    {
        $info['table']  = APPLICATIONS_TBL . ' AS AT LEFT JOIN ' . USER_TBL . ' AS UT ON (AT.uid = UT.uid)';
        $info['debug']  = false;
        $info['where']  = 'AT.application_status = ' . q('Accepted') . ' AND AT.sid = ' . getActiveSessionID();
        $info['fields'] = array('UT.email');
        
        $data['session_year'] = getActiveSessionYear();
        
        $result = select($info);
        
        $data['body'] = createPage(ACCEPTED_EMAIL_TEMPLATE, $data);
        
        //dumpVar($result);
        
        foreach($result as $key => $value)
        {
            $data['email'] = $value->email;
            $ok = sendMail($data);
            
            if ($ok)
            {
                $fp = fopen(DOCUMENT_ROOT . "/documents/accepted_mail_sent_".date('Y-m-d'), "a");
            
                fwrite($fp, $data['email'] . "; Mail Sent: " . $ok . "; Time: " . date('Y-m-d H:i:s') . "\n");
            }
        }
        
        if ($ok)
        {
            $data['accept_mail_sent'] = date('Y-m-d');
            $infop['table']  = SESSIONS_TBL;
            $infop['debug']  = false;
            $infop['where']  = 'id = ' . getActiveSessionID();
            $infop['data']   = $data;
            
            update($infop);
        }
        
        echo $ok;
    }
   
   function acceptApplication()
   {
       $id    = getUserField('id');
       //dumpVar($ids);
       //die;    
       $info['table'] = APPLICATIONS_TBL;
       $info['data']  = array('application_status' => 'Accepted');
       $info['debug'] = true;
       $info['where'] = 'id='. $id;

       $result = update($info);
       
       if($result)
       {
           echo "1";
       }
       else
       {
           echo '0';
       }
   }
   
   function saveRemarks()
   {
       $id         = getUserField('app_id');
       $remarks    = getUserField('remarks');
       //dumpVar($ids);
       //die;    
       $info['table'] = APPLICATIONS_TBL;
       $info['data']  = array('remarks' => $remarks);
       $info['debug'] = false;
       $info['where'] = 'id='. $id;

       $result = update($info);
       
       if($result)
       {
           echo "1";
       }
       else
       {
           echo '0';
       }
       die;
   }
   
   function rejectApplication()
   {
       $id    = getUserField('id');
       //dumpVar($ids);
       //die;    
       $info['table'] = APPLICATIONS_TBL;
       $info['data']  = array('application_status' => 'Rejected');
       $info['debug'] = true;
       $info['where'] = 'id='. $id;

       $result = update($info);
       
       if($result)
       {
           echo "1";
       }
       else
       {
           echo '0';
       }
       die;
   }
   
   function acceptAll()
   {
       $ids    = getUserField('ids');
       //dumpVar($ids);
       //die;    
       $info['table'] = APPLICATIONS_TBL;
       $info['data']  = array('application_status' => 'Accepted');
       $info['debug'] = true;
       $info['where'] = 'id  IN (' . $ids.')';

       $result = update($info);
       
       if($result)
       {
           echo "1";
       }
       else
       {
           echo '0';
       }
       die;
   }
   
   function rejectAll()
   {
       $ids    = getUserField('ids');
       //dumpVar($ids);
       //die;    
       $info['table'] = APPLICATIONS_TBL;
       $info['data']  = array('application_status' => 'Rejected');
       $info['debug'] = true;
       $info['where'] = 'id  IN (' . $ids.')';

       $result = update($info);
       
       if($result)
       {
           echo "1";
       }
       else
       {
           echo '0';
       }
       die;
   }
   
   function viewApplication()
   {
       $id = getUserField('id');
       
       $info['table'] = APPLICATIONS_TBL . ' AS AT LEFT JOIN ' . USER_TBL . ' AS UT ON (AT.uid = UT.uid) LEFT JOIN ' . 
                        GUARDIAN_TBL . ' AS GT ON (AT.uid = GT.uid) LEFT JOIN ' . USER_ADDRESS_TBL . ' AS UAT ON (AT.uid = UAT.user_id) LEFT JOIN ' .
                        TICKETS_TBL . ' AS TT ON (AT.uid = TT.uid)';
       $info['debug'] = false;
       $info['where'] = 'AT.id = ' . $id;
       
       $data = select($info);
       $data[0]->applicant_pic            = getFileLocation($data[0]->photo_id,$data[0]->uid);
       $data[0]->income_tax_doc           = getFileLocation($data[0]->guardian_doc_id,$data[0]->uid);
       $data[0]->ticket_doc               = getFileLocation($data[0]->ticket_doc_id,$data[0]->uid);
       $data[0]->acceptance_letter_file   = getFileLocation($data[0]->acceptance_doc_id,$data[0]->uid);
       $data[0]->scholarship_letter_file  = getFileLocation($data[0]->scholarship_doc_id,$data[0]->uid);
       $data[0]->enroll_file              = getFileLocation($data[0]->enroll_doc_id,$data[0]->uid);
       $data[0]->i20_file                 = getFileLocation($data[0]->i20_doc_id,$data[0]->uid);
       $data[0]->app_id                   = $id;
       $data[0]->academic_qualifications  = getAcademicQualificationList($data[0]->uid);
       //dumpvar($data[0]);
       
       $std_details = createPage(APPLICANT_DETAILS_TEMPLATE, $data[0]);
       
       echo $std_details;
       die;
   }
   
   
   function deleteAcademicDoc()
   {
       $id = getUserField('id');
       
       $info['table'] = ACADEMIC_QUALIFICATIONS_TBL;
       $info['debug'] = false;
       $info['where'] = 'id = ' . $id;
       
       
       if (delete($info))
       {
           echo json_encode('1');
       }
       else
       {
           echo json_encode('');
       }
   }


   function checkDuplicateUser()
   {
       $username = getUserField('username');
       
       $info['table'] = USER_TBL;
       $info['debug'] = false;
       $info['where'] = 'username = ' . q($username);
       
       $result = select($info);
       
       if ( !empty($result) )
       {
           echo json_encode('1');
       }
       else
       {
           echo json_encode('');
       }
   }
   
   function checkDuplicateEmail()
   {
       $email = getUserField('email');
       
       $info['table'] = USER_TBL;
       $info['debug'] = false;
       $info['where'] = 'email = ' . q($email);
       
       $result = select($info);
       
       if ( !empty($result) )
       {
           echo json_encode('1');
       }
       else
       {
           echo json_encode('');
       }       
   }
   
   /**
    * Shows User Editor
    * @param message
    * @return user editor template
    */
   function showNewEditor($msg)
   {
      $data['user_type_list']        = getEnumFieldValues(USER_TBL, 'user_type');
      $data['user_status_list']      = getEnumFieldValues(USER_TBL, 'user_status');
      $data['gender_list']           = getEnumFieldValues(USER_TBL, 'gender');
      $data['maritial_status_list']  = getEnumFieldValues(USER_TBL, 'maritial_status');
      
      //dumpVar($data);
      
      return createPage(APPLICANT_EDITOR_TEMPLATE, $data);
   }

   /**
   * Shows User Editor
   * @param message
   * @return user editor template
   */
   function showEditor($msg)
   {
       $uid = getFromSession('uid'); //getUserField('id'); 
      
       if (!empty($uid))
       {
         $thisUser = new User(array('uid' => $uid));
         
         if( empty($thisUser))
         {
            $thisUser = array();
         }
         
         foreach($thisUser as $key => $value)
         {
            $userData[$key] = $value;	
         }
         
         $data = array_merge(array(), $userData);
      }
      
      $data['message']                     = $msg;
      $data['country_list']                = getCountryList();
      $data['application_status_list']     = getEnumFieldValues(APPLICATIONS_TBL, 'application_status');
      $data['gender_list']                 = getEnumFieldValues(USER_TBL, 'gender');
      $data['degree_list']                 = getEnumFieldValues(ACADEMIC_QUALIFICATIONS_TBL, 'degree');
      $data['session_year_list']           = getSessionYearList();
      $data['session_year']                = getActiveSessionID();
      $data['received_grant_list']         = getEnumFieldValues(APPLICATIONS_TBL, 'received_grant');
      
      return createPage(APPLICANT_EDITOR_TEMPLATE, $data);
   }

   /**
   * Saves User information
   * @return message
   */
   function saveRecord()
   {
      $uid = getFromSession('uid');
      
      if($_FILES['photo']['size'] > 0)
      {
         $_FILES['document'] = $_FILES['photo'];
         $thisDoc = new DocumentEntity();
         $photoID = $thisDoc->addDocument();

         $thisDoc  = new DocumentEntity($photoID);
         $fileName = $thisDoc->getRemoteFileName();

         $arr = explode('.', $fileName);

         $fileLocation = '/' . $uid . '/' . $fileName;
         resampimage(IMG_WIDTH, IMG_HEIGHT, DOCUMENT_REPOSITORY . $fileLocation, DOCUMENT_REPOSITORY . $fileLocation);
         $_SESSION['user_image'] = REL_DOCUMENT_DIR . $fileLocation;
         $_SESSION['photo_id']   = $photoID;
      }

      if($uid)
      {
         $thisUser = new User();

         if($thisUser->modifyUser($photoID, $uid))
         {
            $msg = '<div class="success">' . $this->getMessage(USER_UPDATE_SUCCESS_MSG) . '</div>';
            saveGuardianDetails($uid);
            saveApplicationDetails($uid);
            saveAcademicQualificationsDetails($uid);
            saveTicketFareDetails($uid);
         }
         else
         {
            $msg = '<div class="error">' . $this->getMessage(USER_UPDATE_ERROR_MSG) . '</div>';
         }
      }
      else
      {
         $thisUser = new User();

         if($thisUser->addUser($photoID))
         {
            $msg = '<div class="success">' . $this->getMessage(USER_SAVE_SUCCESS_MSG) . '</div>';
            
         }
         else
         {
            $msg = '<div class="error">' . $this->getMessage(USER_SAVE_ERROR_MSG) . '</div>';
         }
      }
      
      //setUserField('id',  '');
      setUserField('cmd', 'edit');
      
      return $this->showEditor($msg);
   }

   /**
   * deletes user info
   * @return message
   */
   function deleteRecord()
   {
      $userID   = getUserField('id');
      $thisUser = new User();

      $rows  = $thisUser->deleteUser($userID);

      if($rows)
      {
         $msg = $this->getMessage(USER_DELETE_SUCCESS_MSG);
      }
      else
      {
         $msg = $this->getMessage(USER_DELETE_ERROR_MSG);
      }

      setUserField('id',  '');
      setUserField('cmd', '');

      return $this->showEditor($msg);
   }

   /**
   * Shows user list
   * @return user list template
   */
    function showList()
    {
        $data['name']                  = getUserField('applicant_name');
        $data['app_id']                = getUserField('app_id');
        $data['email']                 = getUserField('email');
        $data['country']               = getUserField('country');
        $data['gender']                = getUserField('gender');
        $data['degree']                = getUserField('degree');
        $data['application_status']    = getUserField('application_status');
        $data['guardian_income_max']   = getUserField('guardian_income_max');
        $data['guardian_income_min']   = getUserField('guardian_income_min');
        $data['session_year']          = getUserField('session_year') ? getUserField('session_year') : getActiveSessionID();
        $data['cmd']                   = getUserField('cmd');
        
        $filterClause = '1';

        if ($data['name'])
        {
            $filterClause .= ' AND UT.first_name LIKE ' .  q( '%'.$data['name'].'%') .  ' OR UT.last_name LIKE '.q( '%'.$data['name'].'%');
        }
        
        if ($data['app_id'])
        {
            $filterClause .= ' AND AT.app_id LIKE '.q($data['app_id']);
        }
        
        if ($data['email'])
        {
            $filterClause .= ' AND UT.email LIKE ' .  q( '%'.$data['email'].'%');
        }
        if ($data['country'])
        {
            $filterClause .= ' AND CLT.id = ' . q($data['country']);
        }
        if ($data['gender'])
        {
            $filterClause .= ' AND UT.gender = ' . q($data['gender']);
        }
        if ($data['guardian_income_max'] && $data['guardian_income_min'])
        {
            $filterClause .= ' AND GT.guardian_income >=' . $data['guardian_income_min'] . ' AND GT.guardian_income <= ' . $data['guardian_income_max'];
        }
        else if ($data['guardian_income_max'])
        {
            $filterClause .= ' AND GT.guardian_income <= ' . $data['guardian_income_max'];
        }
        else if ($data['guardian_income_min'])
        {
            $filterClause .= ' AND GT.guardian_income >=' . $data['guardian_income_min'];
        }
        if ($data['application_status'])
        {
            $filterClause .= ' AND AT.application_status = ' . q($data['application_status']);
        }
        else
        {
             $filterClause .= ' AND AT.application_status != ' . q('Not Submitted') . ' AND AT.application_status != ' . q('');
        }
        if ($data['degree'])
        {
            $filterClause .= ' AND AQT.degree IN (' . $data['degree'] . ')';
        }
        if($data['session_year'])
        {
            $filterClause .= ' AND AT.sid = ' . $data['session_year'];
        }
        
      

        $info['table']  = USER_TBL.' AS UT LEFT JOIN ' . 
                          APPLICATIONS_TBL . ' AS AT ON (AT.uid=UT.uid) LEFT JOIN ' . 
                          COUNTRY_LOOKUP_TBL . ' AS CLT ON (AT.country=CLT.id) LEFT JOIN ' . 
                          GUARDIAN_TBL . ' AS GT ON (AT.uid=GT.uid) LEFT JOIN ' . 
                          ACADEMIC_QUALIFICATIONS_TBL . ' AS AQT ON (AT.uid = AQT.uid) LEFT JOIN '. 
                          TICKETS_TBL . ' AS TT ON (AT.uid=TT.uid) LEFT JOIN ' . 
                          SESSIONS_TBL . ' AS ST ON (AT.sid = ST.id) LEFT JOIN ' . 
                          USER_ADDRESS_TBL . ' AS UAT ON (AT.uid = UAT.user_id) LEFT JOIN ' . 
                          AIRFARES_TBL . ' AS AFT ON (AT.country = AFT.country AND AT.destination_airport = AFT.destination_airport)';
        $info['debug']  = false;
        $info['fields'] = array('DISTINCT AT.id', 'AT.app_id', 'CONCAT(UT.first_name, \' \', UT.last_name) AS name', 'UT.email', 'UT.gender', 'AT.submit_date', 'UT.uid AS user_id', 
                                'AT.application_status','AT.remarks', 'CLT.name AS country_name', 'UT.uid','TT.ticket_fare', 'GT.guardian_name', 'GT.guardian_occupation',
                                'IF(GT.guardian_doc_id = 0, \'(Income Certificate not attached)\', \'(Income Certificate attached)\') AS guardian_doc',
                                'GT.guardian_income', 'UAT.present_address', 'UAT.present_phone', 'AT.university_name', 'AT.university_contact', 'AT.subject_desc',
                                'IF(AT.acceptance_doc_id = 0, \'Acceptance Letter not attached\', \'Acceptance Letter attached\') AS acceptance_doc',
                                'IF(AT.scholarship_doc_id = 0, \'Scholarship Letter not attached\', \'Scholarship Letter attached\') AS scholarship_doc', 
                                'IF(TT.ticket_doc_id = 0, \'Air Ticket not attached\', \'Air Ticket attached\') AS ticket_doc',
                                'IF(AT.enroll_doc_id = 0, \'Certificate not Submitted\', \'Certificate Submitted\') AS enroll_doc', 'TT.ticket_fare', 'TT.tax',
                                'TT.total', 'AT.destination_airport', 'AT.grant_amount', 'AFT.local_fare', 'AT.base_fare',
                                'CONCAT("", IF(AT.tofel = 0, "", CONCAT("TOFEL-", AT.tofel, "\n")), 
                                            IF(AT.ielts = 0, "", CONCAT("IELTS-", AT.ielts, "\n")),
                                            IF(AT.sat = 0,   "", CONCAT("SAT-", AT.sat, "\n")),
                                            IF(AT.gre = 0,   "", CONCAT("GRE-", AT.gre, "\n")),
                                            IF(AT.gmat = 0,  "", CONCAT("GMAT-", AT.gmat, "\n"))
                                        ) AS other_degree'
                                );
        $info['where']  = $filterClause .  ' ORDER BY AT.country';

        $result = select($info);
        $count = 0;
        
        if ($result)
        {
            foreach($result as $key=>$value)
            {
                $retData[$value->country_name][$count] = $value; 
                $retData[$value->country_name][$count++]->academic_qualification = $this->getAcademicQualication($value->user_id);
            }
        }
        //dumpVar($retData);
        $data['list'] = $retData;
        //dumpVar($data);
        
        if ($data['cmd'] == 'excel' || $data['cmd'] == 'pdf')
        {    
            //header('Content-Type: text/plain; charset=utf-8');
            //$screen = createPage(PDF_TEMPLATE, $data);
            MakeExcelorPDF($data);
            return;
        }
        
        echo createPage(APPLICANT_LIST_TEMPLATE, $data);
    }
    
    function getAcademicQualication($user_id)
    {
        $info['table']  = ACADEMIC_QUALIFICATIONS_TBL;
        $info['debug']  = false;
        $info['where']  = 'uid = ' . $user_id;
        
        $result = select($info);
        
        if ($result)
        {
            foreach( $result as $key => $value)
            {
                if ($value->degree == 'S.S.C' || $value->degree == 'O Levels' || $value->degree == 'Dakhil')
                {
                    $retData['a'] = $value->result;
                }

                if ($value->degree == 'H.S.C.' || $value->degree == 'A Levels' || $value->degree == 'Alim' || $value->degree == 'IB')
                {
                    $retData['b'] = $value->result;
                }

                if ($value->degree == 'Bachelor' || $value->degree == 'Kamil')
                {
                    $retData['c'] = $value->result;
                }

                if ($value->degree == 'Masters' || $value->degree == 'Fazil')
                {
                    $retData['d'] = $value->result;
                }

                if ($value->degree == 'Ph.D')
                {
                    $retData['e'] = $value->result;
                }
            }
        }
        
        
        return $retData;
    }
}
?>
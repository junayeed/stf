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
           case 'edit'               : $screen = $this->showEditor($msg);      break;
           case 'new'                : $screen = $this->showNewEditor($msg);   break;
           case 'add'                : $screen = $this->saveRecord();          break;
           case 'delete'             : $screen = $this->deleteRecord();        break;
           case 'list'               : $screen = $this->showList();            break;
           case 'excel'              : $screen = $this->showList();            break;
           case 'checkuser'          : $screen = $this->checkDuplicateUser();  break;
           case 'checkemail'         : $screen = $this->checkDuplicateEmail(); break;
           case 'deletedoc'          : $screen = $this->deleteAcademicDoc();   break;
           case 'viewapp'            : $screen = $this->viewApplication();     break;
           case 'acceptall'          : $screen = $this->acceptAll();           break;
           case 'rejectall'          : $screen = $this->rejectAll();           break;
           case 'acceptApplication'  : $screen = $this->acceptApplication();           break;
           case 'rejectApplication'  : $screen = $this->rejectApplication();           break;
           
           default           : $screen = $this->showEditor($msg);
      }

      // Set the current navigation item
      $this->setNavigation('user');
      
      if ($cmd == 'checkuser' || $cmd == 'checkemail' || $cmd=='viewapp' || $cmd=='acceptall' || 
          $cmd == 'rejectall' || $cmd=='acceptApplication' || $cmd=='rejectApplication')
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
       dumpvar($data[0]);
       
       $std_details = createPage(APPLICANT_DETAILS_TEMPLATE, $data[0]);
       
       echo $std_details;
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
      $data['session_year']                = getActiveSessionYear();
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
        $data['email']                 = getUserField('email');
        $data['country']               = getUserField('country');
        $data['gender']                = getUserField('gender');
        $data['degree']                = getUserField('degree');
        $data['application_status']    = getUserField('application_status');
        $data['guardian_income_max']   = getUserField('guardian_income_max');
        $data['guardian_income_min']   = getUserField('guardian_income_min');
        $data['session_year']          = getUserField('session_year') ? getUserField('session_year') : getActiveSessionYear();
        $data['cmd']                   = getUserField('cmd');
        
        $filterClause = '1';

        if ($data['name'])
        {
            $filterClause .= ' AND UT.first_name LIKE ' .  q( '%'.$data['name'].'%') .  ' OR UT.last_name LIKE '.q( '%'.$data['name'].'%');
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
            $filterClause .= ' AND ST.session_year = ' . q($data['session_year']);
        }
        

        $info['table']  = APPLICATIONS_TBL.' AS AT LEFT JOIN ' . USER_TBL . ' AS UT ON (AT.uid=UT.uid) LEFT JOIN ' . 
                          COUNTRY_LOOKUP_TBL . ' AS CLT ON (AT.country=CLT.id) LEFT JOIN ' . GUARDIAN_TBL . ' AS GT ON (AT.uid=GT.uid) LEFT JOIN ' . 
                          ACADEMIC_QUALIFICATIONS_TBL . ' AS AQT ON (AT.uid = AQT.uid) LEFT JOIN '. TICKETS_TBL . ' AS TT ON (AT.uid=TT.uid) LEFT JOIN ' . 
                          SESSIONS_TBL . ' AS ST ON (AT.sid = ST.id) LEFT JOIN ' . USER_ADDRESS_TBL . ' AS UAT ON (AT.uid = UAT.user_id)';
        $info['debug']  = true;
        $info['fields'] = array('DISTINCT AT.id', 'CONCAT(UT.first_name, \' \', UT.last_name) AS name', 'UT.email', 'UT.gender','AT.id', 'AT.submit_date', 
                                'AT.application_status', 'CLT.name AS country_name', 'UT.uid','TT.ticket_fare', 'GT.guardian_name', 'GT.guardian_occupation',
                                'IF(GT.guardian_doc_id = 0, \'(Income Certificate not attached)\', \'(Income Certificate attached)\') AS guardian_doc',
                                'UAT.present_address', 'UAT.present_phone');
        $info['where']  = $filterClause .  ' ORDER BY AT.country';

        $result = select($info);
        
        if ($result)
        {
            foreach($result as $key=>$value)
            {
                $retData[$value->country_name][] = $value; 
            }
        }
        
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
}
?>
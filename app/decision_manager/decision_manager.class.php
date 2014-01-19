<?php

/**
 * File: application_manager.class.php
 *
 * @copyright {@link www.softbizsoltion.com }
 * @author  junayeed@gmail.com
 */

/**
 * The applicationManager application class
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
           case 'edit'       : $screen = $this->showEditor($msg);      break;
           case 'new'        : $screen = $this->showNewEditor($msg);   break;
           case 'add'        : $screen = $this->saveRecord();          break;
           case 'delete'     : $screen = $this->deleteRecord();        break;
           case 'list'       : $screen = $this->showList();            break;
           case 'checkuser'  : $screen = $this->checkDuplicateUser();  break;
           case 'checkemail' : $screen = $this->checkDuplicateEmail(); break;
           case 'deletedoc'  : $screen = $this->deleteAcademicDoc();   break;
           case 'viewapp'    : $screen = $this->viewApplication();     break;
           case 'saveall'    : $screen = $this->saveAll();             break;
          
           
           default           : $screen = $this->showEditor($msg);
      }

      // Set the current navigation item
      $this->setNavigation('user');
      
      if ($cmd == 'checkuser' || $cmd == 'checkemail' || $cmd=='viewapp')
      {
          return;
      }

      if ($cmd == 'list')
      {
         echo $screen;
      }
      else
      {
         echo $this->displayScreen($screen);
      }

      return true;
   }
   
   function saveAll()
   {
        $info['table']  = APPLICATIONS_TBL;
        $info['debug']  = true;
       
       foreach( $_REQUEST as $key => $value)
	{
            if( preg_match('/awarded_amount_(\d+)/', $key, $matches))
            {
                $appID = $matches[1];
                
                $data['awarded_amount']  = str_replace(',','',$_REQUEST['awarded_amount_' . $appID]);
                $info['data']            = $data;
                $info['where']           = 'id ='.$appID;
                $result = update($info);
 	    }
	}
       $this->saveSessionInfo();
   }


   function saveSessionInfo()
   {
       $info['table']  = SESSIONS_TBL;
       $info['debug']  = true;
       
       $info['data'] = getUserDataSet(SESSIONS_TBL);
       $info['where']           = 'id =1';  //need to change this hardcode
       
       $result = update($info);
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
       $data[0]->applicant_pic            = getFileLocation($data[0]->photo_id);
       $data[0]->income_tax_doc           = getFileLocation($data[0]->guardian_doc_id);
       $data[0]->ticket_doc               = getFileLocation($data[0]->ticket_doc_id);
       $data[0]->acceptance_letter_file   = getFileLocation($data[0]->acceptance_doc_id);
       $data[0]->scholarship_letter_file  = getFileLocation($data[0]->scholarship_doc_id);
       $data[0]->enroll_file              = getFileLocation($data[0]->enroll_doc_id);
       $data[0]->i20_file                 = getFileLocation($data[0]->i20_doc_id);
       //dumpvar($data[0]);
       
       $std_details = createPage(DECISION_DETAILS_TEMPLATE, $data[0]);
       
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
      
      return createPage(DECISION_EDITOR_TEMPLATE, $data);
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
      $data                                = $this->showList();
      
      return createPage(DECISION_EDITOR_TEMPLATE, $data);
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
        
        $data['scholarship_bulk_amount']        = currentSessionAmount(1);
        $data['totalTicketFare']                = getTotalTicketFare($sid);
        $data['scholarship_percentage']         = ($data['scholarship_bulk_amount']/ $data['totalTicketFare']);
        $data['awarded_amount']                 = $data['totalTicketFare']*$data['scholarship_percentage'];
        
        $info['table']  = APPLICATIONS_TBL.' AS AT LEFT JOIN ' . USER_TBL . ' AS UT ON (AT.uid=UT.uid) LEFT JOIN ' . 
                          COUNTRY_LOOKUP_TBL . ' AS CLT ON (AT.country=CLT.id) LEFT JOIN ' . TICKETS_TBL . ' AS TT ON (AT.uid=TT.uid)';
        $info['debug']  = false;
        $info['fields'] = array('DISTINCT AT.id', 'CONCAT(UT.first_name, \' \', UT.last_name) AS name', 'UT.gender','AT.id', 'AT.submit_date', 'AT.application_status', 
                                'CLT.name AS country_name', 'UT.uid', 'TT.ticket_fare', 'TT.tax', 'TT.total', 'TT.total*'.$data['scholarship_percentage'].' AS grant_amount');
        $info['where']  = 'AT.application_status = ' . q('Accepted') . '  ORDER BY AT.country';

        $result = select($info);
        
        if ($result)
        {
            foreach($result as $key=>$value)
            {
                $retData[$value->country_name][] = $value; 
            }
        }
        
        $data['list']            = $retData;
        $data['total_applicant'] = count($result);
        
        return $data;
        
        //echo createPage(DECISION_LIST_TEMPLATE, $data);
    }
}
?>
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
           case 'edit'       : $screen = $this->showEditor($msg);     break;
           case 'new'        : $screen = $this->showNewEditor($msg);  break;
           case 'add'        : $screen = $this->saveRecord();         break;
           case 'delete'     : $screen = $this->deleteRecord();       break;
           case 'list'       : $screen = $this->showList();           break;
           case 'checkuser'  : $screen = $this->checkDuplicateUser(); break;
           case 'checkemail' : $screen = $this->checkDuplicateEmail(); break;
           case 'deletedoc'  : $screen = $this->deleteAcademicDoc();   break;
           default           : $screen = $this->showEditor($msg);
      }

      // Set the current navigation item
      $this->setNavigation('user');
      
      if ($cmd == 'checkuser' || $cmd == 'checkemail')
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
        $data['name']      = getUserField('applicant_name');
        $data['email']     = getUserField('email');
        $data['country']   = getUserField('country');
        $data['application_status']   = getUserField('application_status');
        $data['gender']   = getUserField('gender');
        $data['guardian_income_max']   = getUserField('guardian_income_max');
        $data['guardian_income_min']   = getUserField('guardian_income_min');
        
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
        

        $info['table']  = APPLICATIONS_TBL.' AS AT LEFT JOIN ' . USER_TBL . ' AS UT ON (AT.uid=UT.uid) LEFT JOIN ' . 
                          COUNTRY_LOOKUP_TBL . ' AS CLT ON (AT.country=CLT.id) LEFT JOIN ' . GUARDIAN_TBL . ' AS GT ON (AT.uid=GT.uid)';
        $info['debug']  = false;
        $info['fields'] = array('CONCAT(UT.first_name, \' \', UT.last_name) AS name', 'UT.gender','AT.id', 'AT.submit_date', 'AT.application_status', 'CLT.name AS country_name');
        $info['where']  = $filterClause .  ' ORDER BY AT.country';

        $data['list'] = select($info);

        $data['user_type_list']     = getEnumFieldValues(USER_TBL, 'user_type');
        $data['user_status_list']   = getEnumFieldValues(USER_TBL, 'user_status');

        echo createPage(APPLICANT_LIST_TEMPLATE, $data);
    }
}
?>
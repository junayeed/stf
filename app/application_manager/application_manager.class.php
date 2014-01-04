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

class applicationManagerApp extends DefaultApplication
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
      
      return createPage(USER_EDITOR_TEMPLATE, $data);
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
      $data['file']                        = getFileLocation($data['photo_id']);//$fileLocation;
      $data['guardian_file']               = getFileLocation($data['guardian_doc_id']);
      $data['acceptance_letter_file']      = getFileLocation($data['acceptance_doc_id']);
      $data['scholarship_letter_file']     = getFileLocation($data['scholarship_doc_id']);
      $data['enroll_certification_file']   = getFileLocation($data['enroll_doc_id']);
      $data['i20_file']                    = getFileLocation($data['i20_doc_id']);
      $data['ticket_file']                 = getFileLocation($data['ticket_doc_id']);
      $data['gender_list']                 = getEnumFieldValues(USER_TBL, 'gender');
      //dumpVar($data);
      $data['country_list']       = getCountryList();
      
      return createPage(APPLICATION_EDITOR_TEMPLATE, $data);
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
            $msg = $this->getMessage(USER_UPDATE_SUCCESS_MSG);
            saveGuardianDetails($uid);
            saveApplicationDetails($uid);
            saveAcademicQualificationsDetails($uid);
            saveTicketFareDetails($uid);
         }
         else
         {
            $msg = $this->getMessage(USER_UPDATE_ERROR_MSG);
         }
      }
      else
      {
         $thisUser = new User();

         if($thisUser->addUser($photoID))
         {
            $msg = $this->getMessage(USER_SAVE_SUCCESS_MSG);
            
         }
         else
         {
            $msg = $this->getMessage(USER_SAVE_ERROR_MSG);
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
        $user_status = getUserField('user_status');
        $user_type   = getUserField('user_type');
        
        if ($user_type == '') 
        {
            $user_type = $_SESSION['userType'];
        }
        else
        {
            if ($user_type == 'All')
            {
                unset($_SESSION['userType']);
            }    
            else
            {    
                $_SESSION['userType'] = $user_type;
            }
        }
        
        if ($user_status == '') 
        {
            $user_status = $_SESSION['userStatus'];
        }
        else
        {
            if ($user_status == 'All')
            {
                unset($_SESSION['userStatus']);
            }    
            else
            {    
                $_SESSION['userStatus'] = $user_status;
            }
        }

        $filterClause = '1';

        if ($_SESSION['userStatus'])
        {
            $filterClause .= ' and user_status = ' . q(getFromSession('userStatus'));
        }
        if ($_SESSION['userType'])
        {
            $filterClause .= ' and user_type = ' . q(getFromSession('userType'));
        }

        $info['table'] = USER_TBL.' AS UR';
        $info['debug'] = false;
        $info['where'] = $filterClause . ' Order By UR.username ASC';

        $data['list'] = select($info);

        $data['user_type_list']     = getEnumFieldValues(USER_TBL, 'user_type');
        $data['user_status_list']   = getEnumFieldValues(USER_TBL, 'user_status');

        echo createPage(APPLICATION_LIST_TEMPLATE, $data);
    }
    
    
    function saveAttachemnt()
    {
        if($_FILES['document'] > 0)
        {
            //$_FILES['document'] = $file;
            
            $thisDoc = new DocumentEntity();
            $doc_id  = $thisDoc->addDocument();
            
            return $doc_id;
        }
        
        return;
    }
    
}
?>
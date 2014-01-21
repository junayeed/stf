<?php

/**
 * File: application_manager.class.php
 *
 * @copyright {@link www.softbizsoltion.com }
 * @author  junayeed@gmail.com
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
           case 'edit'             : $screen = $this->showEditor($msg);                    break;
           case 'new'              : $screen = $this->showNewEditor($msg);                 break;
           case 'personal-info'    : $screen = $this->saveRecord();                        break;
           case 'ticket-info'      : $screen = $this->saveTicketFareDetails();             break;
           case 'academic-info'    : $screen = $this->saveAcademicQualificationsDetails(); break;
           case 'university-info'  : $screen = $this->saveApplicationDetails();            break;
           case 'submit-app'       : $screen = $this->submitApplication();                 break;
           case 'preview-app'      : $screen = $this->showEditor($msg);                    break;
           case 'submit_app'       : $screen = $this->submitApplication();                 break;
           case 'delete'           : $screen = $this->deleteRecord();                      break;
           case 'list'             : $screen = $this->showList();                          break;
           case 'checkuser'        : $screen = $this->checkDuplicateUser();                break;
           case 'checkemail'       : $screen = $this->checkDuplicateEmail();               break;
           case 'deletedoc'        : $screen = $this->deleteAcademicDoc();                 break;
           case 'city'             : $screen = $this->loadCityByCountry();                 break;
           default                 : $screen = $this->showEditor($msg);
      }

      // Set the current navigation item
      $this->setNavigation('user');
      
      if ($cmd == 'checkuser' || $cmd == 'checkemail' || $cmd == 'city')
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
   
   function loadCityByCountry()
   {
       $country = getUserField('country');
       $retData = '';
       
       $info['table']  = CITY_LOOKUP_TBL;
       $info['debug']  = false;
       $info['fields'] = array('city'); 
       $info['where']  = 'country = ' . q($country) . ' ORDER BY city';
       
       $result = select($info);
       
       if ( $result )
       {
           foreach($result as $value)
           {
               $retData[] = $value->city; 
           }
           
           echo json_encode(implode('###', $retData));
       }
       else
       {
           echo json_encode('');
       }
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
    function showEditor($msg)
    {
        $attachementID = array('photo' => 'photo_id', 'guardian_income_tax' => 'guardian_doc_id', 'acceptance_letter'=>'acceptance_doc_id', 
                               'scholarship_letter'=>'scholarship_doc_id', 'enroll_certification' => 'enroll_doc_id', 'i20' => 'i20_doc_id', 
                               'ticket_doc' => 'ticket_doc_id');
        
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
        
        foreach( $attachementID as $key => $value)
        {
            $data['hasAttachmentArray'][$key] = $data[$value];
        }
        
        $data['message']                     = $msg;
        $data['file']                        = getFileLocation($data['photo_id'], $uid);//$fileLocation;
        $data['guardian_file']               = getFileLocation($data['guardian_doc_id'], $uid);
        $data['acceptance_letter_file']      = getFileLocation($data['acceptance_doc_id'], $uid);
        $data['scholarship_letter_file']     = getFileLocation($data['scholarship_doc_id'], $uid);
        $data['enroll_certification_file']   = getFileLocation($data['enroll_doc_id'], $uid);
        $data['i20_file']                    = getFileLocation($data['i20_doc_id'], $uid);
        $data['ticket_file']                 = getFileLocation($data['ticket_doc_id'], $uid);
        $data['ielts_file']                  = getFileLocation($data['ielts_doc_id'], $uid);
        $data['tofel_file']                  = getFileLocation($data['tofel_doc_id'], $uid);
        $data['gre_file']                    = getFileLocation($data['gre_doc_id'], $uid);
        $data['sat_file']                    = getFileLocation($data['sat_doc_id'], $uid);
        $data['gmat_file']                   = getFileLocation($data['gmat_doc_id'], $uid);
        $data['others_file']                 = getFileLocation($data['others_doc_id'], $uid);
        $data['gender_list']                 = getEnumFieldValues(USER_TBL, 'gender');
        $data['received_grant_list']         = getEnumFieldValues(APPLICATIONS_TBL, 'received_grant');
        //dumpVar($data);
        $data['country_list']                = getCountryList();
        $data['current_tab']                 = getUserField('next_tab') == '' ? 'personal-info' : getUserField('next_tab');
        $data['session_year']                = getActiveSessionYear();
        
        setUserField('id',  $uid);
        setUserField('cmd', 'edit');

        if(getUserField('preview'))
        {
            return createPage(APPLICATION_PREVIEW_TEMPLATE, $data);
        }   
        else 
        {
            return createPage(APPLICATION_EDITOR_TEMPLATE, $data);
        }
    }
    
    function submitApplication()
    {
        $data['application_status']     = getUserField('submitted') ? 'Pending' : 'Not Submitted';
        $data['submit_date']            = getUserField('submitted') ? date('Y-m-d'): null;
        
        $info['table']  = APPLICATIONS_TBL;
        $info['debug']  = false;
        $info['data']   = $data;
        $info['where']  = 'uid = ' . getFromSession('uid');
        
        update($info);
        
        return $this->showEditor($msg);
    }
    
    function saveApplicationDetails()
    {
        $data                           = getUserDataSet(APPLICATIONS_TBL);
        $data['uid']                    = getFromSession('uid');
        $data['sid']                    = getActiveSessionID(); 
        $data['app_id']                 = 'STF-' . getActiveSessionYear() . str_pad($data['uid'], 5, "0", STR_PAD_LEFT); 
        $data['acceptance_doc_id']      = saveAttachment($_FILES['acceptance_letter']);
        $data['scholarship_doc_id']     = saveAttachment($_FILES['scholarship_letter']);
        $data['enroll_doc_id']          = saveAttachment($_FILES['enroll_certification']);
        $data['i20_doc_id']             = saveAttachment($_FILES['i20']);
        $data['tofel_doc_id']           = saveAttachment($_FILES['tofel_doc']);
        $data['ielts_doc_id']           = saveAttachment($_FILES['ielts_doc']);
        $data['sat_doc_id']             = saveAttachment($_FILES['sat_doc']);
        $data['gre_doc_id']             = saveAttachment($_FILES['gre_doc']);
        $data['gmat_doc_id']            = saveAttachment($_FILES['gmat_doc']);
        $data['others_doc_id']          = saveAttachment($_FILES['other_attachment']);
        $data['application_status']     = getUserField('submitted') ? 'Pending' : 'Not Submitted';
        $data['submit_date']            = getUserField('submitted') ? date('Y-m-d'): null;
        $data['received_grant_amount']  = str_replace(',', '', $data['received_grant_amount']);
        
        
        $info['table']  = APPLICATIONS_TBL;
        $info['debug']  = false;
        $info['data']   = $data;
        
        if ( isRecordExistsByUID($data['uid'], APPLICATIONS_TBL) )
        {
            $info['where']  = 'uid = ' . $data['uid'] . ' AND sid = ' . $data['sid']; 
            
            if( update($info) )
            {
                $msg = '<div class="success">University Information has been updated successfully.</div>';
            }
        } 
        else
        {
            $info['data']['create_date']  = date('Y-m-d');
            $result = insert($info);
            
            if ($result)
            {
                $msg = '<div class="success">University Information has been updated successfully.</div>';
            }
        }
        
        return $this->showEditor($msg);
    }
    
    function saveAcademicQualificationsDetails()
    {
        $uid = getFromSession('uid');
        
        $info['table']  = ACADEMIC_QUALIFICATIONS_TBL;
        $info['debug']  = false;
        
        foreach( $_REQUEST as $key => $value)
	{
            if( preg_match('/degree_(\d+)/', $key, $matches))
            {
                $id      = $matches[1];
                $aq_id   = $_REQUEST['aqid_' . $id];

                $data['uid']             = $uid;
                $data['degree']          = $_REQUEST['degree_' . $id];
                $data['result']          = $_REQUEST['result_' . $id];
                $data['attachmentname']  = $_REQUEST['attachmentname_' . $id];
                $data['degree']          = $_REQUEST['degree_' . $id];
                $data['doc_id']          = saveAttachment($_FILES['academicfiles_'.$id]);
                
                $info['data']  = $data;
                if($aq_id)
                {
                    $info['where']  = "id = " . $aq_id;
                    if ( update($info) )
                    {
                        $msg = '<div class="success">Academic Information has been updated successfully.</div>';
                    }
                }
                else 
                {
                    $result = insert($info);
                    
                    if ($result)
                    {
                        $msg = '<div class="success">Academic Information has been updated successfully.</div>';
                    }
                }
                
            }
        }
        
        return $this->showEditor($msg);
    }
    
    function saveTicketFareDetails()
    {
        $uid = getFromSession('uid');
         
        $data                    = getUserDataSet(TICKETS_TBL);
        $data['uid']             = $uid;
        $data['ticket_doc_id']   = saveAttachment($_FILES['ticket_doc']);
        $data['create_date']     = date('Y-m-d');
        
        $info['table']  = TICKETS_TBL;
        $info['debug']  = false;
        $info['data']   = $data;
        
        if ( isRecordExistsByUID($uid, TICKETS_TBL) )
        {
            updateDestinationAirport($uid);
            
            $info['where'] = 'uid = ' . $uid; 
            
            if ( update($info) )
            {
                $msg = '<div class="success">Ticket Information has been updated successfully.</div>';
            }
        } 
        else
        {
            $result = insert($info);
            
            if ( $result )
            {
                $msg = '<div class="success">Ticket Information has been updated successfully.</div>';
            }
        }
        
        return $this->showEditor($msg);
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
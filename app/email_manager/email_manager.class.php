<?php

/**
 * The emailManagerApp application class
 */

class emailManagerApp extends DefaultApplication
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
           case 'edit'         : $screen = $this->showEditor($msg);     break;
           case 'new'          : $screen = $this->showNewEditor($msg);  break;
           case 'add'          : $screen = $this->saveRecord();         break;
           case 'delete'       : $screen = $this->deleteRecord();       break;
           case 'list'         : $screen = $this->showList();           break;
           case 'checksession' : $screen = $this->checkActiveSession(); break;
           default             : $screen = $this->showEditor($msg);     break;
      }
      
      if ($cmd == 'checksession')
      {
          return;
      }

      // Set the current navigation item
      $this->setNavigation('user');
      
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
   
   function checkActiveSession()
   {
       $info['table']  = SESSIONS_TBL;
       $info['debug']  = false;
       $info['where']  = 'session_status = ' . q('Active');
       
       $result = select($info);
       
       if ($result)
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
        $data['message'] = $msg;
        $data['session_status_list']  = getEnumFieldValues(SESSIONS_TBL, 'session_status');
        
        return createPage(SESSION_EDITOR_TEMPLATE, $data);
    }

    /**
    * Shows User Editor
    * @param message
    * @return user editor template
    */
    function showEditor($msg)
    {
        $id = getUserField('id');

        if (!empty($id))
        {
            $thisData = getSessionDetails($id);
            
            if( empty($thisData))
            {
                $thisData = array();
            }

            foreach($thisData as $key => $value)
            {
                $sessionData[$key] = $value;	
            }

            $data = array_merge(array(), $sessionData);
        }
        
        $data['message'] = $msg;
        $data['session_status_list']  = getEnumFieldValues(SESSIONS_TBL, 'session_status');
        
        return createPage(EMAIL_EDITOR_TEMPLATE, $data);
    }

    /**
    * Saves User information
    * @return message
    */
    function saveRecord()
    {
        $ID  = getUserField('id');

        if($ID)
        {
            if(updateSession($ID))
            {
                $msg = $this->getMessage(EMAIL_UPDATE_SUCCESS_MSG);
            }
            else
            {
                $msg = $this->getMessage(EMAIL_UPDATE_ERROR_MSG);
            }
        }
        else
        {
            if(addSession())
            {
                $msg = $this->getMessage(EMAIL_SAVE_SUCCESS_MSG);
            }
            else
            {
                $msg = $this->getMessage(EMAIL_SAVE_ERROR_MSG);
            }
        }
        
        setUserField('id',  '');
        setUserField('cmd', '');

        return $this->showEditor($msg);
    }

    /**
    * deletes user info
    * @return message
    */
    function deleteRecord()
    {
        $ID   = getUserField('id');

        $rows  = deleteMagazine($ID);

        if($rows)
        {
            $msg = $this->getMessage(EMAIL_DELETE_SUCCESS_MSG);
        }
        else
        {
            $msg = $this->getMessage(EMAIL_DELETE_ERROR_MSG);
        }

        setUserField('id',  '');
        setUserField('cmd', '');

        return $this->showNewEditor($msg);
    }

    /**
    * Shows user list
    * @return user list template
    */
    function showList()
    {
        $info['table']  = SESSIONS_TBL;
        $info['debug']  = false;
        $data['list']   = select($info);
        

        //dumpVar($data);

        echo createPage(EMAIL_LIST_TEMPLATE, $data);
    }
} 
?>
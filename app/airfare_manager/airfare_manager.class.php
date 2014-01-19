<?php

/**
 * File: airfare_manager.class.php
 *
 */

/**
 * The airfareManagerApp application class
 */

class airfareManagerApp extends DefaultApplication
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
           case 'add'          : $screen = $this->saveRecord();         break;
           case 'list'         : $screen = $this->showList();           break;
           //case 'search'       : $screen = $this->showEditor($msg);     break;
           default             : $screen = $this->showEditor($msg);     break;
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
   
    /**
    * Shows User Editor
    * @param message
    * @return user editor template
    */
    function showEditor($msg)
    {
        $session_year = getUserField('session_year');

        if (!empty($session_year))
        {
            $thisData = $this->getCountryListBySession($session_year);
            
            if( empty($thisData))
            {
                $thisData = array();
            }

            foreach($thisData as $key => $value)
            {
                $airfareData[$key] = $value;	
            }

            $data['country_list'] = array_merge(array(), $airfareData);
        }
        
        //dumpvar($data);
        
        $data['message']              = $msg;
        $data['session_year']         = $session_year;
        $data['session_year_list']    = getSessionYearList();
        
        return createPage(AIRFARE_EDITOR_TEMPLATE, $data);
    }
    
    function getCountryListBySession($session_year)
    {
        $info['table']  = APPLICATIONS_TBL . ' AS AT LEFT JOIN ' . COUNTRY_LOOKUP_TBL . ' AS CLT ON (AT.country = CLT.id) LEFT JOIN ' . 
                          AIRFARES_TBL . ' AS AFT ON (AT.country = AFT.country)';
        $info['debug']  = false;
        $info['where']  = 'AT.sid = ' . $session_year;
        $info['fields'] = array('DISTINCT(CLT.name) AS country_name', 'CLT.id AS country', 'AFT.source', 'AFT.local_fare');
        
        $result = select($info);
        
        if ($result)
        {
            foreach($result as $value)
            {
                $retData[$value->country] = $value;
            }
            
            return $retData;
        }
        else
        {
            return array();
        }
    }

    /**
    * Saves User information
    * @return message
    */
    function saveRecord()
    {
        dumpVar($_REQUEST);
        $ID  = getUserField('id');
        
        foreach( $_REQUEST as $key => $value)
	{
            if( preg_match('/local_fare_([A-Z]+)/', $key, $matches))
            {
                $id = $matches[1];
                dumpVar($matches);
                echo_br($id);
                
                $data['country']     = $id;
                $data['local_fare']  = $_REQUEST['local_fare_' . $id];
                $data['source']      = $_REQUEST['source_' . $id];
            }
            dumpVar($data);
        }
        
        

//        if($ID)
//        {
//            if(updateSession($ID))
//            {
//                $msg = $this->getMessage(SESSION_UPDATE_SUCCESS_MSG);
//            }
//            else
//            {
//                $msg = $this->getMessage(SESSION_UPDATE_ERROR_MSG);
//            }
//        }
//        else
//        {
//            if(addSession())
//            {
//                $msg = $this->getMessage(SESSION_SAVE_SUCCESS_MSG);
//            }
//            else
//            {
//                $msg = $this->getMessage(SESSION_SAVE_ERROR_MSG);
//            }
//        }
        
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
        $info['table']  = SESSIONS_TBL;
        $info['debug']  = false;
        $data['list']   = select($info);
        

        //dumpVar($data);

        echo createPage(AIRFARE_LIST_TEMPLATE, $data);
    }
} 
?>
<?php
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
        $data['session_year_list']    = getSessionYearList();
        $data['sid']                  = getActiveSessionID();
        $session_year                 = getUserField('session_year');
        $session_year                 = $session_year ? $session_year : array_search (getActiveSessionYear(), $data['session_year_list']);

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
            if ($airfareData)
            {
                $data['country_list'] = array_merge(array(), $airfareData);
            }
        }
        
        $data['message']              = $msg;
        $data['session_year']         = $session_year;
        
        return createPage(AIRFARE_EDITOR_TEMPLATE, $data);
    }
    
    function getCountryListBySession($session_year)
    {
        $info['table']  =  APPLICATIONS_TBL . ' AS AT LEFT JOIN ' . AIRFARES_TBL . ' AS AFT ON (AT.country=AFT.country AND AT.destination_airport = AFT.destination_airport) LEFT JOIN ' . COUNTRY_LOOKUP_TBL . ' AS CLT ON (AFT.country=CLT.id)';
        $info['debug']  = false;
        $info['where']  = 'AT.sid = ' . $session_year .' AND AT.application_status = ' . q('Accepted') . ' ORDER BY CLT.name';
        $info['fields'] = array('CLT.name AS country_name', 'CLT.id AS country', 'AFT.source', 'AFT.local_fare', 'AT.destination_airport');
        
        $result = select($info);
        //dumpVar($result);
        if ($result)
        {
            foreach($result as $value)
            {
                $retData[$value->country_name][$value->destination_airport] = $value;
            }
            //dumpVar($retData);
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
        $sid = getUserField('sid');
        
        $this->deleteRecord($sid);
        
        $info['table']  = AIRFARES_TBL;
        $info['debug']  = false;
        //dumpVar($_REQUEST);
        
        foreach( $_REQUEST as $key => $value)
	{
            if( preg_match('/local_fare_([A-Za-z\s-_]+)/i', $key, $matches))
            {
                //dumpVar($matches);
                $id = $matches[1];
                $data['destination_airport']  = str_replace("_", " ", $id);
                $data['local_fare']           = $_REQUEST['local_fare_' . $id];
                $data['country']              = $_REQUEST['country_' . $id];
                $data['source']               = $_REQUEST['source_' . $id];
                $data['sid']                  = $sid;
                $data['create_date']          = date('Y-m-d');
                
                $info['data']        = $data;
                insert($info);
            }
        }
        
        return $this->showEditor($msg);
    }
    
    function deleteRecord($sid)
    {
        $info['table']  = AIRFARES_TBL;
        $info['debug']  = false;
        $info['where']  = 'sid = ' . $sid;
        
        delete($info);
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
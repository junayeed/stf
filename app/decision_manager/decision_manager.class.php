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
        $info['table']  = APPLICATIONS_TBL;
        $info['debug']  = true;
       
        foreach( $_REQUEST as $key => $value)
	{
            if( preg_match('/grant_amount_(\d+)/', $key, $matches))
            {
                $application_id = $matches[1];
                
	        $data['grant_amount']      = str_replace(',', '', $_REQUEST['grant_amount_' . $application_id]);
                
                $info['data']  = $data;
                $info['where'] = 'id = ' . $application_id;
                
                update($info);
 	    }
	}
        
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
        $sid = getActiveSessionID();
        $data['scholarship_bulk_amount']        = currentSessionAmount($sid);
        $data['totalTicketFare']                = getTotalTicketFare($sid);
        //$data['scholarship_percentage']         = ($data['scholarship_bulk_amount'] / $data['totalTicketFare']);
        $data['scholarship_percentage']         = getScheloarshipPercentage($data['scholarship_bulk_amount'], $data['totalTicketFare']);
        $data['awarded_amount']                 = 0;
        
        $info['table']  = APPLICATIONS_TBL.' AS AT LEFT JOIN ' . USER_TBL . ' AS UT ON (AT.uid=UT.uid) LEFT JOIN ' . 
                          COUNTRY_LOOKUP_TBL . ' AS CLT ON (AT.country=CLT.id) LEFT JOIN ' . TICKETS_TBL . ' AS TT ON (AT.uid=TT.uid) LEFT JOIN ' . 
                          AIRFARES_TBL . ' AS AFT ON (AT.destination_airport = AFT.destination_airport)';
        $info['debug']  = false;
        $info['fields'] = array('DISTINCT AT.id', 'CONCAT(UT.first_name, \' \', UT.last_name) AS name', 'UT.gender','AT.id', 'AT.submit_date',  
                                'CLT.name AS country_name', 'UT.uid', 'TT.ticket_fare', 'TT.tax', 'TT.total', 'AT.destination_airport','AT.application_status',
                                'AFT.local_fare');
        $info['where']  = 'AT.application_status = ' . q('Accepted') . ' ORDER BY AT.country';

        $result = select($info); 
        //dumpVar($result);
        
        if ($result)
        {
            foreach($result as $key=>$value)
            {
                $retData[$value->country_name][$value->destination_airport][] = $value; 
            }
        }
        
        //dumpVar($retData);
        
        foreach( $retData as $country_key => $country)
        {
            foreach($country as $city_key => $city_list)
            {
                $min  = $this->getMinValue($city_list);
               //echo_br("Country = " . $country_key . " City = " . $city_key . " Min = " . $min);
                foreach($city_list as $key => $value)
                {
                    $retData[$country_key][$city_key][$key]->base_fare = $value->local_fare < $min ? $value->local_fare : $min;
                    $retData[$country_key][$city_key][$key]->grant_amount = $this->roundAmount ($retData[$country_key][$city_key][$key]->base_fare * $data['scholarship_percentage'] );
                    $data['awarded_amount'] += $retData[$country_key][$city_key][$key]->grant_amount;
                }
            }
        }
        //dumpVar($retData);
        $data['list']            = $retData;
        $data['total_applicant'] = count($result);
        
        return $data;
        
        //echo createPage(DECISION_LIST_TEMPLATE, $data);
    }
    
    function getMinValue($list)
    {
        $min = 9999999;
        
        foreach($list as $value)
        {
            if ( $value->ticket_fare < $min)
            {
                $min = $value->total;
            }
        }
        
        return $min;
    }
    
    function roundAmount($value)
    {
        if ( $value % 5 == 0)
            return (int) $value;
        
        return (int) $value+(5-$value % 5);
    }
}
?>
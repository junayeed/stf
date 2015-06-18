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

class reportManagerApp extends DefaultApplication
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
        $cmd = getUserField('cmd');
      
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
      
        if ($cmd == 'pdf')
        {
            $screen = createPage(REPORT_PDF_TEMPLATE, $data);
            makePDF($screen);
        }
      
        return createPage(REPORT_EDITOR_TEMPLATE, $data);
    }
    
    

    /**
     * Shows user list
     * @return user list template
     */
    function showList()
    {
        $sid = getActiveSessionID();
        
        $info['table']  = APPLICATIONS_TBL.' AS AT LEFT JOIN ' . USER_TBL . ' AS UT ON (AT.uid=UT.uid) LEFT JOIN ' . 
                          GUARDIAN_TBL . ' AS GT ON (AT.uid = GT.uid) LEFT JOIN ' .
                          COUNTRY_LOOKUP_TBL . ' AS CLT ON (AT.country=CLT.id) LEFT JOIN ' . TICKETS_TBL . ' AS TT ON (AT.uid=TT.uid) LEFT JOIN ' . 
                          AIRFARES_TBL . ' AS AFT ON (AT.destination_airport = AFT.destination_airport)';
        $info['debug']  = false;
        $info['fields'] = array('DISTINCT AT.id', 'CONCAT(UT.first_name, \' \', UT.last_name) AS name', 'UT.gender','AT.id', 'AT.submit_date',  
                                'CLT.name AS country_name', 'UT.uid', 'TT.ticket_fare', 'TT.tax', 'TT.total', 'AT.destination_airport',
                                'AT.application_status', 'AFT.local_fare', 'GT.guardian_name', 'GT.guardian_occupation', 'GT.guardian_income',
                                'AT.grant_amount');
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
        
        $data['list']            = $retData; //dumpVar($data);
        
        return $data;
    }
    
    function getMinValue($list)
    {
        $min = 9999999;
        
        foreach($list as $value)
        {
            //if ( $value->ticket_fare < $min)
            if ( $value->total < $min)
            {
                $min = $value->total;
                //$min = $value->ticket_fare;
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
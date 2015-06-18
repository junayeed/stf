<?php

    function addsession()
    {
        $data                 = getUserDataSet(SESSIONS_TBL);
        $data['session_year'] = getUserField('session_year');  
        $data['create_date']  = date('Y-m-d');

        $info['table']  = SESSIONS_TBL;
        $info['debug']  = false; 	
        $info['data']   = $data;

        $result = insert($info);
        
        if ( $result['newid'] );
        {
            return true;
        }

        return false;
    }
   
    function updateSession($ID)
    {
        $data                             = getUserDataSet(SESSIONS_TBL);
        $data['session_year']             = getUserField('session_year'); 
        $data['scholarship_bulk_amount']  = str_replace(',','', $data['scholarship_bulk_amount']);
        $data['awarded_amount']           = str_replace(',','', $data['awarded_amount']);
        
        $info['table'] = SESSIONS_TBL;
        $info['debug'] = false;
        $info['where'] = 'id = ' . $ID;
        $info['data']  = $data;

        return update($info);
   }
   
   function deleteMagazine($ID)
   {
        $info['table']  = SESSIONS_TBL;
        $info['debug']  = false;
        $info['where']  = 'id  = ' . $ID;

        return delete($info);
   }
   
   function getSessionDetails($ID)
   {
        $info['table']  = SESSIONS_TBL;
        $info['debug']  = false;
        $info['where']  = 'id  = ' . $ID;

        $result = select($info);     
        $temp = explode('-', $result[0]->session_year);

        $result[0]->session_year1 = $temp[0];
        $result[0]->session_year2 = $temp[1];

        if($result)
        {
            return $result[0];
        }
   }
?>
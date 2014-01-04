<?php

    function addMagazine()
    {
        $data                 = getUserDataSet(MAGAZINES_TBL);
        $data['create_date']  = date('Y-m-d');

        $info['table']  = MAGAZINES_TBL;
        $info['debug']  = false; 	
        $info['data']   = $data;

        $result = insert($info);
        
        if ( $result['newid'] );
        {
            return true;
        }

        return false;
    }
   
   function updateMagazine($ID)
   {
      $data          = getUserDataSet(MAGAZINES_TBL);
      $info['table'] = MAGAZINES_TBL;
      $info['debug'] = false;
      $info['where'] = 'id = ' . $ID;
      $info['data']  = $data;
      
      return update($info);
   }
   
   function deleteMagazine($ID)
   {
      $info['table']  = MAGAZINES_TBL;
      $info['debug']  = false;
      $info['where']  = 'id  = ' . $ID;
      
      return delete($info);
   }
   
   function getMagazineInfo($ID)
   {
      $info['table']  = MAGAZINES_TBL;
      $info['debug']  = false;
      $info['where']  = 'id  = ' . $ID;
      
      $result = select($info);     
      
      if($result)
      {
          return $result[0];
      }
   }
   
   function getTotalRowCount()
    {
        $info['table']  = MAGAZINES_TBL;
        $info['debug']  = false;
        $info['fields'] = array('COUNT(*) AS total_rows');

        $result = select($info);
        
        return $result[0]->total_rows;        
    }
?>
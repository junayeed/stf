<?php

    function addProductGroup()
    {
        $data                 = getUserDataSet(PRODUCT_GROUP_TBL);
        $data['create_date']  = date('Y-m-d');

        $info['table']  = PRODUCT_GROUP_TBL;
        $info['debug']  = false; 	
        $info['data']   = $data;

        $result = insert($info);
        
        if ( $result['newid'] );
        {
            return true;
        }

        return false;
    }
   
   function updateProductGroup($ID)
   {
      $data          = getUserDataSet(PRODUCT_GROUP_TBL);
      $info['table'] = PRODUCT_GROUP_TBL;
      $info['debug'] = false;
      $info['where'] = 'id = ' . $ID;
      $info['data']  = $data;
      
      return update($info);
   }
   
   function deleteProductGroup($ID)
   {
      $info['table']  = PRODUCT_GROUP_TBL;
      $info['debug']  = false;
      $info['where']  = 'id  = ' . $ID;
      
      return delete($info);
   }
   
   function getProductGroupInfo($ID)
   {
      $info['table']  = PRODUCT_GROUP_TBL;
      $info['debug']  = false;
      $info['where']  = 'id  = ' . $ID;
      
      $result = select($info);     
      
      if($result)
      {
          return $result[0];
      }
   }
?>
<?php

    function addProductGroup()
    {
        $data                 = getUserDataSet(PRODUCT_TBL);
        $data['create_date']  = date('Y-m-d');

        $info['table']  = PRODUCT_TBL;
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
      $data          = getUserDataSet(PRODUCT_TBL);
      
      if ( empty($data['include']) )
      {
          $data['include'] = 'No';
      }
      $info['table'] = PRODUCT_TBL;
      $info['debug'] = false;
      $info['where'] = 'id = ' . $ID;
      $info['data']  = $data;
      
      return update($info);
   }
   
    function deleteProduct($ID)
    {
        $data['product_status']  = PRODUCT_ARCHIVE;
       
        $info['table']  = PRODUCT_TBL;
        $info['debug']  = false;
        $info['where']  = 'id  = ' . $ID;
        $info['data']  = $data;
 
        // update the product status to Archive rather that delete the product
        // if we delete the product, product description will be lost
        return update($info);
   }
   
   function getProductDetails($ID)
   {
      $info['table']  = PRODUCT_TBL;
      $info['debug']  = false;
      $info['where']  = 'id  = ' . $ID;
      
      $result = select($info); 
      
      if($result)
      {
          return $result[0];
      }
   }
   
    function getMagazineCode()
    {
        
        $info['table']  = MAGAZINES_TBL;
        $info['debug']  = false;

        $result = select($info);     

        if($result)
        {
            foreach($result as $key=>$value)
            {
                //$retData[$value->id] = $value->magazine_name . ' (' . $value->magazine_abvr . ')';
                $retData[$value->id] = $value->magazine_abvr;
            }
        }
        
        return $retData;
    }
    
    function getProductGroupList()
    {
        $info['table']  = PRODUCT_GROUP_TBL;
        $info['debug']  = false;

        $result = select($info);     

        if($result)
        {
            foreach($result as $key=>$value)
            {
                $retData[$value->id] = $value->product_group;
            }
        }
        
        return $retData;
    }
    
    function getTotalRowCount($whereClause)
    {
        $info['table']  = PRODUCT_TBL . ' AS PT LEFT JOIN ' . MAGAZINES_TBL . ' AS MT ON PT.magazine_code = MT.id LEFT JOIN ' . PRODUCT_GROUP_TBL . ' AS PGT ON PT.product_group = PGT.id';;
        $info['where']  = $whereClause;
        $info['debug']  = false;
        $info['fields'] = array('COUNT(*) AS total_rows');

        $result = select($info);     

        return $result[0]->total_rows;        
    }
        
    function getProductSizeList()
    {
        $info['table']  = PRODUCT_TBL;
        $info['debug']  = false;
        $info['fields'] = array('DISTINCT(qty_per_unit)');
        $info['where']  = '1 ORDER BY qty_per_unit ASC';
        
        $result = select($info);
        
        if($result)
        {
            foreach($result as $key => $value)
            {
                $retData[$value->qty_per_unit] = $value->qty_per_unit;
            }
        }
        
        return $retData;
    }
?>
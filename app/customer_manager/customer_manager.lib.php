<?php

    function addCustomer()
    {
        $data                 = getUserDataSet(CUSTOMERS_TBL);
        $data['created_on']   = date('Y-m-d');

        $info['table']  = CUSTOMERS_TBL;
        $info['debug']  = false; 	
        $info['data']   = $data;

        $result = insert($info);
        
        if ( $result['newid'] );
        {
            //addCapsuleOrganization($data);
            return true;
        }

        return false;
    }
    
    function addCapsuleOrganization($data)
    {
        $capsule_token  = getCapsuleToken();
        $organization   = array(
                              'contacts' => array(
                                                  'email'   => array('type' => 'Work','emailAddress' => $data['email']),
                                                  'address' => array('street' => $data['address'], 'city' => $data['city'], 'state' => $data['county'], 'zip' => $data['postcode'])
                                                  ),
                              'name' => $data['company_name'],
                              );
        
        dumpVar($organization);
        
        try 
        {
            $capsule = new Services_Capsule($capsule_token->app_name, $capsule_token->capsule_token);
            $party   = $capsule->organization->add($organization);
        } 
        catch (Services_Capsule_Exception $e) 
        {
            echo '<pre>';
            print_r($e);
            echo '</pre>';
        }
        
        dumpVar($party);
    }
   
    function updateCustomer($ID)
    {
        $data          = getUserDataSet(CUSTOMERS_TBL);
      
        $info['table'] = CUSTOMERS_TBL;
        $info['debug'] = false;
        $info['where'] = 'id = ' . $ID;
        $info['data']  = $data;
      
        return update($info);
    }
   
    function deleteCustomer($ID)
    {
        deleteOrderDetailsByCustomerID($ID);
        deleteOrderHistoryByCustomerID($ID);
        deleteArtworkDetailsByCustomerID($ID);
        deleteScheduleDetailsByCustomerID($ID);
        
        $info['table']  = CUSTOMERS_TBL;
        $info['debug']  = false;
        $info['where']  = 'id  = ' . $ID;
        
        return delete($info);
    }
    
    function deleteOrderDetailsByCustomerID($customer_id)
    {
        $info['table']  = ORDER_DETAILS_TBL;
        $info['debug']  = false;
        $info['where']  = 'customer_id = ' . $customer_id;
        
        return delete($info);
    }
    
    function deleteOrderHistoryByCustomerID($customer_id)
    {
        $info['table']  = ORDER_HISTORY_TBL;
        $info['debug']  = true;
        $info['where']  = 'customer_id = ' . $customer_id;
        
        return delete($info);
    }
    
    function deleteArtworkDetailsByCustomerID($customer_id)
    {
        $info['table']  = ARTWORK_DETAILS_TBL;
        $info['debug']  = true;
        $info['where']  = 'customer_id = ' . $customer_id;
        
        return delete($info);
    }
    
    function deleteScheduleDetailsByCustomerID($customer_id)
    {
        $info['table']  = SCHEDULE_DETAILS_TBL;
        $info['debug']  = true;
        $info['where']  = 'customer_id = ' . $customer_id;
        
        return delete($info);
    }
   
    function getCustomerInfo($ID)
    {
        $info['table']  = CUSTOMERS_TBL;
        $info['debug']  = false;
        $info['where']  = 'id  = ' . $ID;

        $result = select($info);     

        if($result)
        {
            return $result[0];
        }
    }
   
    function getTotalRowCount($whereClause)
    {
        $info['table']  = CUSTOMERS_TBL . ' AS CT';// . ' AS PT LEFT JOIN ' . MAGAZINES_TBL . ' AS MT ON PT.magazine_code = MT.id LEFT JOIN ' . PRODUCT_GROUP_TBL . ' AS PGT ON PT.product_group = PGT.id';;
        $info['where']  = $whereClause;
        $info['debug']  = false;
        $info['fields'] = array('COUNT(*) AS total_rows');

        $result = select($info);     

        return $result[0]->total_rows;        
    }
?>
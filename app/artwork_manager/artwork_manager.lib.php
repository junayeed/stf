<?php
    
    function addOrderInfo()
    {
        $data               = getUserDataSet(ORDER_TBL);
        $data['order_date'] = date('Y-m-d');
        
        $info['data']   = $data;
        $info['table']  = ORDER_TBL;
        $info['debug']  = false;
        
        $result = insert($info);
        
        if ( $result['newid'] );
        {
            addOrderDetails($result['newid']);
            return true;
        }

        return false;
    }
    
    function addOrderDetails($customer_id)
    {
        $retdata = false;
        
        // then insert order details in the database
        $info['table']  = ARTWORK_DETAILS_TBL;
        $info['debug']  = false;
        
        foreach( $_REQUEST as $key => $value)
	{
            if( preg_match('/order_no_(\d+)/', $key, $matches))
            {
                $id = $matches[1];
                
                $orderDate = explode('-', $_REQUEST['order_date_' . $id]);

                $data['customer_id']       = $customer_id;
                $data['product_id']        = $_REQUEST['pid_' . $id];
	        $data['shape']             = $_REQUEST['shape_' . $id];
	        $data['position']          = $_REQUEST['position_' . $id];
	        $data['status']            = $_REQUEST['artworkstatus_' . $id];
	        $data['instruction']       = $_REQUEST['instruction_' . $id];
	        $data['order_details_id']  = $_REQUEST['order_details_id_' . $id];
	        $data['artwork_id']        = $_REQUEST['artwork_id_' . $id];
                
                if ( $data['artwork_id'] )
                {
                    $updated = updateOrderDetails($data);
                }
                else
                {
                    addArtworkDetails($data);
                }
                
                if ($updated) // if order details is successfully updated add a new record in order history table
                {
                    //addOrderHistory($customer_id, $data['order_details_id'], EDITED);
                }
                
                $retdata = $retdata || $updated;
	    }
	}
        
        return $retdata;
    }
    
    function updateOrderDetails($data)
    {
        $info['table']  = ARTWORK_DETAILS_TBL;
        $info['debug']  = false;
        $info['where']  = 'id = ' . $data['artwork_id'];
        $info['data']   = $data;
        
        if(update($info))
        {
            addOrderHistoryRecord(ARTWORK_HISTORY_TBL, $data['customer_id'], $data['order_details_id'], EDITED);
            return true;   
        }
        else
        {
            return false;
        }
    }
    
    function addArtworkDetails($data)
    {
        $data['create_date']  = date('Y-m-d');
        $info['table']        = ARTWORK_DETAILS_TBL;
        $info['debug']        = false;
        $info['data']         = $data;
        
        $result = insert($info);
        
        if ( $result )
        {
            addOrderHistoryRecord(ARTWORK_HISTORY_TBL, $data['customer_id'], $data['order_details_id'], ADDED);
            return true;
        }
        else 
        {
            return false;
        }
    }

    function updateOrderInfo($ID)
    {
        //echo_br('updateOrderInfo ORDER ID ::: ' . $ID);
        $data               = getUserDataSet(ORDER_TBL);
        $data['placed_by']  = $_SESSION['uid'];
        $data['updated_on'] = date('Y-m-d');
        
        $info['data']   = $data;
        $info['table']  = ORDER_TBL;
        $info['debug']  = false;
        $info['where']  = 'id = ' . $ID;
        
        //if order is updated try to add/update order details table and return true
        update($info);

        if (addOrderDetails($ID))
        {
            return true;
        }
        //else return false
        return false;
    }
    
    function getOrderInfo($cust_id)
    {
        $info['table']   = CUSTOMERS_TBL . ' AS CT LEFT JOIN ' . UK_CITY_TBL . ' AS UCT ON (CT.county = UCT.id)';
        $info['debug']   = false;
        $info['where']   = 'CT.id = ' . $cust_id;
        $info['fields']  = array('CT.id AS customer_id', 'CT.company_name', 'CT.town', 'CT.county', 
                                 'CT.postcode', 'CT.email', 'CT.address', 'CT.artwork_notes', 'UCT.city_name', 'CT.order_notes');
        
        $result = select($info);
        
        if ( !empty($result))
        {
            return $result[0];
        }
        else
        {
            return array();
        }
    }
    
    function getOrderDetails($customer_id)
    {
        //$info['table']   = ORDER_TBL . ' AS OT LEFT JOIN ' . ORDER_DETAILS_TBL . ' AS ODT ON (OT.id = ODT.order_id) LEFT JOIN  ' . PRODUCT_TBL . ' AS PT ON (ODT.product_id = PT.id) LEFT JOIN ' . CUSTOMERS_TBL . ' AS CT ON (OT.customer_id = CT.id) LEFT JOIN ' . USER_TBL . ' AS UT ON (ODT.placed_by = UT.uid)';
        $info['table']   = ORDER_DETAILS_TBL . ' AS ODT LEFT JOIN  ' . PRODUCT_TBL . ' AS PT ON (ODT.product_id = PT.id) LEFT JOIN ' . 
                           ARTWORK_DETAILS_TBL . ' AS ADT ON (ODT.id = ADT.order_details_id) ';
        $info['debug']   = false;
        $info['where']   = 'ODT.customer_id = ' . $customer_id . ' AND PT.product_option = ' . q('Artwork'). ' ORDER BY ODT.id ASC';
        $info['fields']  = array('ODT.customer_id', 'ODT.id AS order_details_id' ,'ODT.product_id', 
                                 'ODT.start_month', 'ODT.end_month', 'ODT.magazine_code', 'PT.qty_per_unit', 'PT.product_status', 
                                 'ADT.id AS artwork_id','ADT.shape', 'ADT.status', 'ADT.position', 'ADT.instruction');
        
        $result = select($info);
        
        if ($result)
        {
            return array_reverse($result);
        }
    }
    
    function getArtworkNotes($order_details_id, $customer_id)
    {
        $info['table']  = ARTWORK_NOTES_TBL;
        $info['debug']  = false;
        $info['where']  = 'order_details_id = ' . $order_details_id . ' AND customer_id = ' . $customer_id;
        
        $result = select($info);
        
        if ($result)
        {
            foreach($result as $key => $value)
            {
                $retData[] = $value->notes;
            }
        }
        
        return $retData;
    }
    
    function getProductGroupList()
    {
        $info['table']  = PRODUCT_GROUP_TBL;
        $info['fields'] = array('id', 'pg_code');
        $info['debug']  = false;

        $result = select($info);     

        return $result;
    }
    
    function deleteOrderHistory($customer_id, $order_details_id)
    {
        $info['table']  = ORDER_HISTORY_TBL;
        $info['debug']  = false;
        $info['where']  = 'customer_id = ' . $customer_id . ' AND order_details_id = ' . $order_details_id;
         
        return delete($info);
    }
    
    function getArtworkHistory($customer_id)
    {
        $info['table']  = ARTWORK_HISTORY_TBL . ' AS OHT LEFT JOIN ' . USER_TBL . ' AS UT ON (OHT.updated_by = UT.uid)';
        $info['debug']  = false;
        $info['where']  = 'customer_id = ' . $customer_id . ' ORDER BY OHT.id DESC LIMIT 0, 15';
        $info['fields'] = array("CONCAT(first_name, ' ', last_name) as uptdaed_by", "order_details_id", "order_status AS artwork_status", "update_date");
        
        $result = select($info);
        
        if ( !empty($result) )
        {
            return $result;
        }
    }
    
    function updateCustomerLastAccess($customer_id)
    {
        $info['table']               = CUSTOMERS_TBL;
        $info['debug']               = false;
        $info['where']               = 'id = ' . $customer_id;
        $info['data']['last_access'] = date('Y-m-d H:i:s');
        
        update($info);
    }
?>
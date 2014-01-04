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
        
        //dumpVar($_REQUEST);
        
        foreach( $_REQUEST as $key => $value)
	{
            if( preg_match('/order_no_(\d+)/', $key, $matches))
            {
                $id = $matches[1];
                
                $data['customer_id']       = $customer_id;
                $data['product_id']        = $_REQUEST['pid_' . $id];
	        $data['shape']             = $_REQUEST['shape_' . $id];
	        $data['position']          = $_REQUEST['position_' . $id];
	        $data['start_month']       = $_REQUEST['start_month_' . $id];
	        $data['end_month']         = $_REQUEST['end_month_' . $id];
	        $data['file_name']         = $_REQUEST['file_name_' . $id];
	        $data['instruction']       = $_REQUEST['instruction_' . $id];
	        $data['order_details_id']  = $_REQUEST['order_details_id_' . $id];
	        $data['schedule_id']       = isset($_REQUEST['schedule_id_' . $id]) ? $_REQUEST['schedule_id_' . $id] : '';
                
                if ( isset($_REQUEST['date_sensitive_' . $id]) )
                {
                    $data['date_sensitive']    = $_REQUEST['date_sensitive_' . $id];
                }
                else 
                {
                    $data['date_sensitive']    = 'No';
                }
                
                if ( $data['schedule_id'] )
                {
                    $updated = updateScheduleDetails($data);
                }
                else
                {
                    addScheduleDetails($data);
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
    
    function updateScheduleDetails($data)
    {
        $info['table']  = SCHEDULE_DETAILS_TBL;
        $info['debug']  = false;
        $info['where']  = 'id = ' . $data['schedule_id'];
        $info['data']   = $data;
        
        if(update($info))
        {
            //addArtworkHistory($data['customer_id'], $data['order_details_id'], EDITED);
            return true;   
        }
        else
        {
            return false;
        }
    }
    
    function addScheduleDetails($data)
    {
        $data['create_date']  = date('Y-m-d');
        $info['table']        = SCHEDULE_DETAILS_TBL;
        $info['debug']        = false;
        $info['data']         = $data;
        
        $result = insert($info);
        
        if ( $result )
        {
            //addArtworkHistory($data['customer_id'], $data['order_details_id'], ADDED);
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
        
        if ( !empty($result) )
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
                           SCHEDULE_DETAILS_TBL . ' AS SDT ON (ODT.id = SDT.order_details_id) ';
        $info['debug']   = false;
        $info['where']   = 'ODT.customer_id = ' . $customer_id . ' AND PT.product_option = ' . q('Schedule'). ' ORDER BY ODT.id ASC';
        $info['fields']  = array('ODT.customer_id', 'ODT.id AS order_details_id' ,'ODT.product_id', 'ODT.start_month AS order_start_month', 
                                 'ODT.end_month AS order_end_month', 'ODT.magazine_code', 'PT.qty_per_unit', 'PT.product_status',
                                 'SDT.id AS schedule_id','SDT.start_month', 'SDT.end_month', 'SDT.position', 'SDT.file_name', 
                                 'SDT.date_sensitive', 'SDT.instruction');
        
        $result = select($info);
        
        //dumpVar($result);
        if ($result)
        {
            return array_reverse ($result);
        }
    }
    
    function getScheduleDetails($customer_id)
    {
        $info['table']  = SCHEDULE_DETAILS_TBL . ' AS SDT LEFT JOIN ' . ORDER_DETAILS_TBL . ' AS ODT ON (SDT.order_details_id = ODT.id) LEFT JOIN ' . 
                          PRODUCT_TBL . ' AS PT ON (SDT.product_id = PT.id)';
        $info['debug']  = false;
        $info['where']  = 'SDT.customer_id = ' . $customer_id . ' ORDER BY SDT.id ASC';
        $info['fields'] = array('SDT.customer_id', 'ODT.id AS order_details_id' ,'ODT.product_id', 'ODT.start_month AS order_start_month', 
                                 'ODT.end_month AS order_end_month', 'ODT.magazine_code', 'PT.qty_per_unit', 'PT.product_status',
                                 'SDT.id AS schedule_id','SDT.start_month', 'SDT.end_month', 'SDT.position', 'SDT.file_name', 
                                 'SDT.date_sensitive', 'SDT.instruction');
        
        $result = select($info);
        
        //dumpVar($result);
        
        if ($result)
        {
            return $result;
        }
    }
    
    function isScheduleExists($customer_id)
    {
        $info['table']  = SCHEDULE_DETAILS_TBL;
        $info['debug']  = false;
        $info['where']  = 'customer_id = ' . $customer_id;
        
        $result = select($info);
        
        if ( $result )
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    function deleteOrderHistory($customer_id, $order_details_id)
    {
        $info['table']  = ORDER_HISTORY_TBL;
        $info['debug']  = false;
        $info['where']  = 'customer_id = ' . $customer_id . ' AND order_details_id = ' . $order_details_id;
         
        return delete($info);
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
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
        $info['table']  = ORDER_DETAILS_TBL;
        $info['debug']  = false;
        
        foreach( $_REQUEST as $key => $value)
	{
            if( preg_match('/order_no_(\d+)/', $key, $matches))
            {
                $id = $matches[1];
                
                $orderDate = explode('-', $_REQUEST['order_date_' . $id]);

                $data['customer_id']       = $customer_id;
                $data['product_id']        = $_REQUEST['pid_' . $id];
                $data['magazine_code']     = $_REQUEST['magazine_code_' . $id] ? $_REQUEST['magazine_code_' . $id] : 0;
	        $data['start_month']       = $_REQUEST['start_month_' . $id];
	        $data['end_month']         = $_REQUEST['end_month_' . $id];
	        $data['alternative']       = $_REQUEST['alternative_' . $id] ? $_REQUEST['alternative_' . $id] : '';
	        $data['page']              = $_REQUEST['page_' . $id];
	        $data['qty']               = $_REQUEST['qty_' . $id];
	        $data['unit_price']        = $_REQUEST['price_' . $id];
	        $data['discount']          = $_REQUEST['discount_' . $id] ? $_REQUEST['discount_' . $id] : 0.00;
	        $data['total']             = $_REQUEST['total_' . $id];
	        $data['status']            = $_REQUEST['productstatus_' . $id];
	        //$data['order_date']        = date('Y-m-d');
	        $data['order_details_id']  = $_REQUEST['order_details_id_' . $id];
                
                if ( !$data['product_id'] ) // if order is submitted w/o any data.
                {
                    deleteOrderDetails($data['order_details_id']);
                    deleteOrderHistory($customer_id, $data['order_details_id']);
                    continue;
                }
                
                $info['data']            = $data;
                
                $updated = updateOrderDetails($data);
                
                if ($updated) // if order details is successfully updated add a new record in order history table
                {
                    addOrderHistory($customer_id, $data['order_details_id'], EDITED);
                }
                
                $retdata = $retdata || $updated;
 	    }
	}
        
        return $retdata;
    }
    
    function updateOrderDetails($data)
    {
        $info['table']  = ORDER_DETAILS_TBL;
        $info['debug']  = false;
        $info['where']  = 'id = ' . $data['order_details_id'];
        $info['data']   = $data;
        
        return update($info);
    }
    
    function deleteOrder($order_id)
    {
        $info['table']  = ORDER_TBL;
        $info['where']  = 'id = ' . $order_id;
        $info['debug']  = false;
        
        if (delete($info) || deleteOrderDetails($order_id))
        {
            return true;
        }
        
        return false;
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
                                 'CT.postcode', 'CT.email', 'CT.address', 'CT.order_notes', 'UCT.city_name');
        
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
        $data['page_no'] = getUserField('page_no');
        $data['page_no'] = $data['page_no'] ? $data['page_no'] : 0;

        //$info['table']   = ORDER_TBL . ' AS OT LEFT JOIN ' . ORDER_DETAILS_TBL . ' AS ODT ON (OT.id = ODT.order_id) LEFT JOIN  ' . PRODUCT_TBL . ' AS PT ON (ODT.product_id = PT.id) LEFT JOIN ' . CUSTOMERS_TBL . ' AS CT ON (OT.customer_id = CT.id) LEFT JOIN ' . USER_TBL . ' AS UT ON (ODT.placed_by = UT.uid)';
        $info['table']   = ORDER_DETAILS_TBL . ' AS ODT LEFT JOIN  ' . PRODUCT_TBL . ' AS PT ON (ODT.product_id = PT.id)';
        $info['debug']   = false;
        $info['where']   = 'ODT.customer_id = ' . $customer_id . ' ORDER BY order_details_id DESC LIMIT ' . $data['page_no']*ROWS_PER_PAGE . ', ' . ROWS_PER_PAGE;
        $info['fields']  = array('ODT.customer_id', 'PT.product_code', 'ODT.id AS order_details_id' ,'ODT.product_id', 
                                 'PT.description', 'ODT.start_month', 'ODT.end_month', 'ODT.alternative', 'ODT.qty', 'ODT.discount', 
                                 'ODT.total', 'ODT.status', 'ODT.order_date', 'ODT.magazine_code', 'PT.product_group', 'PT.product_status', 
                                 'ODT.unit_price', 'ODT.page');
        
        $result = select($info);
        
        if ( empty ($result) )
        {
            return;
        }
        
        return array_reverse($result);
        //return $result;
    }
    
    function getTotalRowCount($customer_id)
    {
        $info['table']  = ORDER_DETAILS_TBL . ' AS ODT LEFT JOIN  ' . PRODUCT_TBL . ' AS PT ON (ODT.product_id = PT.id)';
        $info['where']  = 'ODT.customer_id = ' . $customer_id . ' ORDER BY ODT.id ASC';
        $info['debug']  = false;
        $info['fields'] = array('COUNT(*) AS total_rows');

        $result = select($info);     

        return $result[0]->total_rows;        
    }
    
    function getProductGroupList()
    {
        $info['table']  = PRODUCT_GROUP_TBL;
        $info['fields'] = array('id', 'pg_code');
        $info['debug']  = false;

        $result = select($info);     

        return $result;
    }
    
    function getMagazines()
    {
        $info['table']  = MAGAZINES_TBL;
        $info['fields'] = array('id', 'magazine_abvr');
        $info['debug']  = false;
        $info['where']  = '1 ORDER BY magazine_abvr ASC';

        $result = select($info);     

        return $result;
    }
    
    function addOrderHistory($customer_id, $order_details_id, $order_status)
    {
        $data['customer_id']       = $customer_id;
        $data['order_details_id']  = $order_details_id;
        $data['update_date']       = date('Y-m-d');
        $data['updated_by']        = $_SESSION['uid'];
        $data['order_status']      = $order_status;
        
        $info['table']  = ORDER_HISTORY_TBL;
        $info['debug']  = false;
        $info['data']   = $data;
        
        insert($info);
    }
    
    function deleteOrderHistory($customer_id, $order_details_id)
    {
        $info['table']  = ORDER_HISTORY_TBL;
        $info['debug']  = false;
        $info['where']  = 'customer_id = ' . $customer_id . ' AND order_details_id = ' . $order_details_id;
         
        return delete($info);
    }
    
    function getOrderHistory($customer_id)
    {
        $info['table']  = ORDER_HISTORY_TBL . ' AS OHT LEFT JOIN ' . USER_TBL . ' AS UT ON (OHT.updated_by = UT.uid)';
        $info['debug']  = false;
        $info['where']  = 'customer_id = ' . $customer_id . ' ORDER BY OHT.id DESC LIMIT 0, 10';
        $info['fields'] = array("CONCAT(first_name, ' ', last_name) as uptdaed_by", "order_details_id", "order_status", "update_date");
        
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
    
    function getEmptyOrderIDs($customerID)
    {
        $info['table']  = ORDER_DETAILS_TBL;
        $info['debug']  = false; 
        $info['where']  = "customer_id = " . $customerID . " AND product_id = 0" ;
        $info['fields'] = array('id');
        
        $result = select($info);
        
        if ( $result )
        {
            foreach($result AS $item => $value)
            {
                $retData[] = $value->id;
            }
        
            return implode(",", $retData);
        }
        else
        {
            return '';
        }
    }
    
    function removeEmptyOrder($customerID)
    {
        $orderIDs = getEmptyOrderIDs($customerID);
        
        $info['table']  = ORDER_DETAILS_TBL;
        $info['debug']  = false; 
        $info['where']  = "customer_id = " . $customerID . " AND product_id = 0" ;
        
        // delete the empty order of current customer 
        delete($info);
        
        // delete the hisotry for those orders
        if ( !empty($orderIDs) )
        {
            removeOrderHistory($customerID, $orderIDs);
        }
    }
    
    function removeOrderHistory($customerID, $orderIDs)
    {
        $info['table']  = ORDER_HISTORY_TBL;
        $info['debug']  = false; 
        $info['where']  = "customer_id = " . $customerID . " AND order_details_id IN (" . $orderIDs . ")" ;
        
        // delete the empty order of current customer 
        delete($info);
    }
    
    function deleteArtworkDetails($order_details_id, $customer_id)
    {
        $info['table']  = ARTWORK_DETAILS_TBL;
        $info['debug']  = false;
        $info['where']  = 'customer_id = ' . $customer_id . ' AND order_details_id = ' . $order_details_id;
        
        if ( delete($info) )
        {
            //addArtworkHistory($customer_id, $order_details_id, DELETED);  // Update the status in the Artwork History
        }
    }
    
    function deleteScheduleDetails($order_details_id, $customer_id)
    {
        $info['table']  = SCHEDULE_DETAILS_TBL;
        $info['debug']  = false;
        $info['where']  = 'customer_id = ' . $customer_id . ' AND order_details_id = ' . $order_details_id;
        
        delete($info);
    }    
?>
<?php

/**
 * File: order_manager.class.php
 *
 * @copyright {@link www.softbizsoltion.com }
 * @author  junayeed@gmail.com
 */

/**
 * The orderManager application class
 */

class orderManagerApp extends DefaultApplication
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
            case 'edit'                     : $screen = $this->showEditor($msg);             break;
            case 'new'                      : $screen = $this->showNewEditor($msg);          break;
            case 'add'                      : $screen = $this->saveRecord();                 break;
            case 'delete'                   : $screen = $this->deleteRecord();               break;
            case 'list'                     : $screen = $this->showList();                   break;
            case 'productlist'              : $screen = $this->showProductList();            break;
            case 'productdetails'           : $screen = $this->getProductDetails();          break;
            case 'customerlist'             : $screen = $this->getCustomerList();            break;
            case 'deleteorder'              : $screen = $this->deleteOrderDetails();         break;
            case 'getCustomerAutoComplete'  : $screen = $this->getCustomerAutoComplete();    break;   
            case 'neworder'                 : $screen = $this->getNewOrderID();              break;   
            case 'addschedule'              : $screen = $this->addOrderSchedule();           break;   
            case 'notes'                    : $screen = $this->saveOrderNotes();             break;   
            case 'cancel'                   : $screen = $this->cancelOrders();               break;   
            default                         : $screen = $this->showEditor($msg);
        }

        // Set the current navigation item
        $this->setNavigation('user');
      
        if ($cmd == 'productlist' || $cmd == 'productdetails' || $cmd == 'customerlist' || $cmd == 'instruction' ||
            $cmd == 'getCustomerAutoComplete' || $cmd == 'deleteorder' || $cmd == 'neworder' || $cmd == 'addschedule')
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
    
    function cancelOrders()
    {
        $customer_id = getUserField('customer_id');
        $order_ids   = getUserField('new_order_ids');
        
        $info['table']  = ORDER_DETAILS_TBL;
        $info['debug']  = false;
        $info['where']  = 'id IN (' . $order_ids . ')';
        
        delete($info);
        
        $info['table']  = ORDER_HISTORY_TBL;
        $info['debug']  = false;
        $info['where']  = 'order_details_id IN (' . $order_ids . ') AND customer_id = ' . $customer_id;
        
        delete($info);
        
        return $this->showEditor('');
    }
    
    function saveOrderNotes()
    {
        $customer_id   = getUserField('customer_id');
        $data          = getUserDataSet(CUSTOMERS_TBL);
        
        $data['order_notes'] = nl2br($data['order_notes']);
        
        $info['table']  = CUSTOMERS_TBL;
        $info['debug']  = false;
        $info['data']   = $data;
        $info['where']  = 'id = ' . $customer_id;
        
        if ( update($info) )
        {
            echo json_encode('1');
            die;
        }
        else
        {
            echo json_encode('');
            die;
        }
    }
    
    function addOrderSchedule()
    {
        $data = getUserDataSet(SCHEDULE_DETAILS_TBL);
        
        $info['table']  = SCHEDULE_DETAILS_TBL;
        $info['debug']  = false;
        $info['data']   = $data;
        
        $result = insert($info);
        
        if ($result['newid'])
        {
            echo json_encode($result['newid']);
            die;
        }
        else
        {
            echo json_encode(''); 
        }
    }
    
    function getNewOrderID()
    {
        $data['customer_id']  = getUserField('customer_id');
        $data['order_date']   = date('Y-m-d');
        $info['table']        = ORDER_DETAILS_TBL;
        $info['debug']        = false;
        $info['data']         = $data;
        
        $result = insert($info);
        
        // add a new record in the order history table with ADDED status
        addOrderHistory($data['customer_id'], $result['newid'], ADDED);
        
        //$newOrderID = getAutoIncrementID('order_details');
        
        if ( $result['newid'] )
        {
            echo json_encode($result['newid']);
            die;
        }    
        else
        {
            echo json_encode('');
        }
    }
    function deleteOrderDetails()
    {
        $order_details_id = getUserField('order_details_id');
        $customer_id      = getUserField('customer_id');
        $product_id       = getUserField('product_id');
        
        // deleting from order_details table
        $info['table']  = ORDER_DETAILS_TBL;
        $info['where']  = 'id = ' . $order_details_id;
        $info['debug']  = false;
        
        if (delete($info))
        {
            
            addOrderHistoryRecord(ORDER_HISTORY_TBL, $customer_id, $order_details_id , DELETED); // on successful delete add a new record in the order history table
            
            if( isArtworkProduct($product_id) )
            {
                deleteOrderDetails(ARTWORK_DETAILS_TBL, $order_details_id, $customer_id);  // Delete the order in the Artwork Details
                addOrderHistoryRecord(ARTWORK_HISTORY_TBL, $customer_id, $order_details_id , DELETED); // on successful delete add a new record in the artwork history table
            }
            
            if ( isScheuleProduct($product_id) )
            {
                deleteOrderDetails(SCHEDULE_DETAILS_TBL, $order_details_id, $customer_id);  // delete the order in the Schedule Details
            }
            
            echo json_encode('1');
            die;
        }
        else
        {
            echo json_encode('0');
        }
    }
    
    function getCustomerList()
    {
        $info['table']  = CUSTOMERS_TBL;
        $info['debug']  = false;
        $info['fields'] = array("id", "CONCAT(first_name, ' ', last_name) as customer_name");
        
        $result = select($info);
        
        if ( !empty($result) )
        {
            foreach($result as $key => $value)
            {
                $retData[$key] = $value->customer_name;
            }
            echo json_encode($retData);
        }
        else
        {
            echo json_encode('');
        }
    }
    
    function getCustomerAutoComplete()
    {
        $info['table']   = CUSTOMERS_TBL;
        $info['debug']   = false;
        $info['where']   = 'status = ' . q('Active');
        $info['fields']  = array("id", "company_name");
        
        $result = select($info);
        
        if ( !empty($result) )
        {
            foreach($result as $key => $value)
            {
                $retData[$value->id] = $value->company_name;
            }
            
            echo json_encode($retData);
            die;
        }
        else
        {
            echo json_encode('');
            die;
        }
    }
   
    function getProductDetails()
    {
        $product_id = getUserField('id');

        $info['table']  = PRODUCT_TBL;
        $info['debug']  = false;
        $info['where']  = 'id = ' . $product_id;

        $result  = select($info);

        if ( !empty($result) )
        {
            $data = $result[0]->id . '$$$' . 
                    $result[0]->product_code . '$$$' . 
                    $result[0]->description . '$$$' . 
                    $result[0]->qty_per_unit . '$$$' . 
                    $result[0]->unit_price . '$$$' . 
                    $result[0]->magazine_code . '$$$' . 
                    $result[0]->product_option;

            echo json_encode($data);
        }
        else
        {
            echo json_encode('');
        }
    }
   
    function showProductList()
    {
        //$product_grp_id  = getUserField('pg_id');

        $info['table']   = PRODUCT_TBL . ' AS PT LEFT JOIN ' . MAGAZINES_TBL . ' AS MT ON (PT.magazine_code = MT.id) ';
        $info['debug']   = false;
        $info['where']   = 'PT.product_status = ' . q('Active') . ' AND PT.include = ' . q('Yes');
        $info['fields']  = array('PT.id', 'PT.product_code', 'MT.magazine_abvr ', 'MT.id AS magazine_id');

        $result  = select($info);
        
        if ( !empty($result) )
        {
            foreach($result as $key => $value)
            {
                //$retData .= $value->id . '$$$' . $value->product_code. '$$$' . $value->description . '$$$' . $value->unit_price . '###';
                //$retData[$value->id.'##'.$value->magazine_id] = $value->magazine_abvr . ':' . $value->product_code;
                $retData[$value->id] = $value->product_code;
            }
            
            echo json_encode($retData);
            die;
        }
        else
        {
            echo json_encode('');
        }
    }
   
    /**
    * Shows User Editor
    * @param message
    * @return user editor template
    */
    function showNewEditor($msg)
    {
        //$data['order_status_list']    = getEnumFieldValues(ORDER_TBL, 'order_status');
        $data['full_product_str']     = getProductList();
        $data['product_group_list']   = getProductGroupList();
        $data['magazine_list']        = getMagazineList();

        return createPage(ORDER_EDITOR_TEMPLATE, $data);
    }

    /**
    * Shows User Editor
    * @param message
    * @return user editor template
    */
    function showEditor($msg)
    {
        $data['customer_id']  = getUserField('customer_id');
        
        removeEmptyOrder($data['customer_id']);
        
        updateCustomerLastAccess($data['customer_id']);

        if (!empty($data['customer_id']))
        {
            $thisOrder = getOrderInfo($data['customer_id']);

            if( empty($thisOrder))
            {
                $thisOrder = array();
            }

            foreach($thisOrder as $key => $value)
            {
                $orderData[$key] = $value;	
            }

            $data                    = array_merge(array(), $orderData);
            $data['order_history']   = getOrderHistory($data['customer_id']);
        }
        
        $data['page_no']           = getUserField('page_no') <= 0 ? '' : getUserField('page_no');
        $data['product_list']      = getOrderDetails($data['customer_id']);
        $data['message']           = $msg;
        $data['magazine_list']     = getMagazines();
        $data['row_count']         = getTotalRowCount($data['customer_id']);
        $data['page_count']        = ceil ( $data['row_count']/ROWS_PER_PAGE );
        
        //dumpVar($_REQUEST);
        
        return createPage(ORDER_EDITOR_TEMPLATE, $data);
    }

    /**
    * Saves User information
    * @return message
    */
    function saveRecord()
    {
        $customer_id = getUserField('customer_id');
        
        if($customer_id)
        {
            if(addOrderDetails($customer_id))
            {
                $msg = $this->getMessage(ORDER_SAVE_SUCCESS_MSG);
            }
            else
            {
                $msg = $this->getMessage(ORDER_SAVE_ERROR_MSG);
            }
        }      

        //setUserField('customer_id',  '');
        //setUserField('cmd', '');

        return $this->showEditor($msg);
        //header('Location: /app/customer_manager/customer_manager.php?msg='.$msg);
    }

    /**
    * deletes order info and order details information
    * @return message
    */
    function deleteRecord()
    {
        $order_id   = getUserField('id');
        $rows       = deleteOrderDetails($order_id);

        // upon successful delete of all order details, delete from order table
        if ( deleteOrder($order_id) )
        {
            $msg = $this->getMessage(ORDER_DELETE_SUCCESS_MSG);
        }
        else
        {
            $msg = $this->getMessage(ORDER_DELETE_ERROR_MSG);
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
        $data['order_status'] = getUserField('order_status');
        $data['user_type']   = getUserField('user_type');

        $filterClause = '1';

        if ($data['order_status'] )
            $filterClause .= ' and order_status = ' . q($data['order_status']);
        if ($data['user_type'])
            $filterClause .= ' and user_type = ' . q($data['user_type']);

        $info['table']   = CUSTOMERS_TBL;
        $info['debug']   = false;
        $info['where']   = $filterClause; //. ' Order By UR.username ASC';
        $info['fields']  = array('company_name AS customer_name');

        $data['list'] = select($info);
        //dumpVar($data);

        //$data['order_status_list']  = getEnumFieldValues(ORDER_TBL, 'order_status');
        $data['user_status_list']   = getEnumFieldValues(USER_TBL, 'user_status');
        
        echo createPage(ORDER_LIST_TEMPLATE, $data);
    }
}
?>
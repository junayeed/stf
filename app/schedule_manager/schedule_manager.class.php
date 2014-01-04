<?php

/**
 * File: schedule_manager.class.php
 *
 * @copyright {@link www.softbizsoltion.com }
 * @author  junayeed@gmail.com
 */

/**
 * The artworkManager application class
 */

class artworkManagerApp extends DefaultApplication
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
            case 'deleteschedule'           : $screen = $this->deleteScheduleDetails();      break;
            case 'list'                     : $screen = $this->showList();                   break;
            default                         : $screen = $this->showEditor($msg);
        }

        // Set the current navigation item
        $this->setNavigation('user');
      
        if ($cmd == 'deleteschedule')
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
    
    function saveArtworkNotes()
    {
        $customer_id   = getUserField('customer_id');
        $data          = getUserDataSet(CUSTOMERS_TBL);
        
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

        return createPage(SCHEDULE_EDITOR_TEMPLATE, $data);
    }

    /**
    * Shows User Editor
    * @param message
    * @return user editor template
    */
    function showEditor($msg)
    {
        $data['customer_id']  = getUserField('customer_id');
        
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

            $data                     = array_merge(array(), $orderData);
        }
        
        if ( isScheduleExists($data['customer_id']) )
        {
            $data['product_list']  = getScheduleDetails($data['customer_id']);
        }
        else
        {    
            $data['product_list']      = getOrderDetails($data['customer_id']);
        }
        $data['message']           = $msg;
        $data['magazine_list']     = getMagazineList();
        
        return createPage(SCHEDULE_EDITOR_TEMPLATE, $data);
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
    }

    /**
    * deletes order info and order details information
    * @return message
    */
    function deleteScheduleDetails()
    {
        $schedule_id = getUserField('schedule_id');
        
        $info['table']  = SCHEDULE_DETAILS_TBL;
        $info['where']  = 'id = ' . $schedule_id;
        $info['debug']  = false;
        
        if( delete($info) )
        {
            echo json_encode('1');
            die;
        }
        else
        {
            echo json_encode('0');
            die;
        }
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
        
        echo createPage(SCHEDULE_LIST_TEMPLATE, $data);
    }
}
?>
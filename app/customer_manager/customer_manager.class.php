<?php

/**
 * File: customer_manager.class.php
 *
 */

/**
 * The customerManagerApp application class
 */

class customerManagerApp extends DefaultApplication
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
            case 'edit'           : $screen = $this->showEditor($msg);             break;
            case 'new'            : $screen = $this->showNewEditor($msg);          break;
            case 'add'            : $screen = $this->saveRecord();                 break;
            case 'delete'         : $screen = $this->deleteRecord();               break;
            case 'list'           : $screen = $this->showList();                   break;
            case 'chkemail'       : $screen = $this->checkDuplicateEmail();        break;
            case 'chkcompany'     : $screen = $this->isCompanyExists();            break;
            case 'custstatus'     : $screen = $this->checkCustomerStatus();        break;
            case 'capsule'        : $screen = $this->getCapsuleInfo();             break;
            default               : $screen = $this->showEditor($msg);             break;
        }

        // Set the current navigation item
        $this->setNavigation('customers');

        if ($cmd == 'chkemail' || $cmd == 'custstatus' || $cmd == 'capsule' || $cmd == 'chkcompany')
            return;

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
    
    function getCapsuleInfo()
    {
        $not_found      = false;
        $capsule_id     = getUserField('capsule_id');
        $capsule_token  = getCapsuleToken();
    
        try 
        {
            //$capsule = new Services_Capsule('discovermv', 'ffc5faad63e41301bb68a90ac7c07116');
            $capsule = new Services_Capsule($capsule_token->app_name, $capsule_token->capsule_token);
            $party   = $capsule->party->get(23724140);
            //$party   = $capsule->party->get($capsule_id);
        } 
        catch (Services_Capsule_Exception $e) 
        {
            //print_r($e);
            $not_found = true;
        }
        
        //dumpvar($party);
        if ($not_found)
        {
            echo json_encode('');
        }
        else if ($party->organisation)
        {
            $data['type']          = 'Organisation';
            $data['company_name']  = $party->organisation->name;
            $data['address']       = $party->organisation->contacts->address->street;
            $data['town']          = $party->organisation->contacts->address->city;
            $data['county']        = $party->organisation->contacts->address->state;
            $data['postcode']      = $party->organisation->contacts->address->zip;
            $data['email']         = $party->organisation->contacts->email->emailAddress;
            
            echo json_encode($data);
        }
        else
        {
            $data['type']          = 'Person';
            $data['first_name']    = $party->person->firstName;
            $data['last_name']     = $party->person->lastName;
            $data['address']       = $party->person->contacts->address->street;
            $data['town']          = $party->person->contacts->address->city;
            $data['county']        = $party->person->contacts->address->state;
            $data['postcode']      = $party->person->contacts->address->zip;
            $data['email']         = $party->person->contacts->email->emailAddress;            
            
            echo json_encode($data);
        }
    }
    
    function checkCustomerStatus()
    {
        $customerID  = getUserField('id');
        
        $info['table']  = ORDER_TBL;
        $info['where']  = 'customer_id = ' . $customerID;
        $info['fields'] = array('COUNT(id) as order_no');
        $info['debug']  = false;
        
        $result = select($info);
        
        if ( !empty($result) )
        {
            echo json_encode($result[0]->order_no);
        }
        else
        {
            echo json_encode('');
        }
    }
   
    function checkDuplicateEmail()
    {
        $email = getUserField('email');

        $info['table'] = CUSTOMERS_TBL;
        $info['debug'] = false;
        $info['where'] = 'email = ' . q($email);

        $result = select($info);

        if ( !empty($result) )
        {
            echo json_encode('1');
        }
        else
        {
            echo json_encode('');
        }
    }
    
    function isCompanyExists()
    {
        $company_name = getUserField('company_name');

        $info['table'] = CUSTOMERS_TBL;
        $info['debug'] = false;
        $info['where'] = 'company_name = ' . q($company_name);

        $result = select($info);

        if ( !empty($result) )
        {
            echo json_encode('1');
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
        $data['message']             = $msg;
        $data['county_list']         = getUKCityList();
        $data['status_list']         = getEnumFieldValues(CUSTOMERS_TBL, 'status');
        
        return createPage(CUSTOMER_EDITOR_TEMPLATE, $data);
    }

    /**
    * Shows User Editor
    * @param message
    * @return user editor template
    */
    function showEditor($msg)
    {
        $id = getUserField('id');

        if (!empty($id))
        {
            $thisCustomer = getCustomerInfo($id);

            if( empty($thisCustomer))
            {
                $thisCustomer = array();
            }

            foreach($thisCustomer as $key => $value)
            {
                $custData[$key] = $value;	
            }

            $data = array_merge(array(), $custData);
        }

        $data['message']             = $msg;
        $data['params']              = 'customer_name=' . getUserField('customer_name') . '&company_name='. getUserField('company_name');
        $data['county_list']         = getUKCityList();
        $data['status_list']         = getEnumFieldValues(CUSTOMERS_TBL, 'status');

        return createPage(CUSTOMER_EDITOR_TEMPLATE, $data);
    }

    /**
    * Saves User information
    * @return message
    */
    function saveRecord()
    {
        $ID  = getUserField('id');
        
        if($ID)
        {
            if(updateCustomer($ID))
            {
                $msg = $this->getMessage(CUSTOMER_UPDATE_SUCCESS_MSG);
            }
            else
            {
                $msg = $this->getMessage(CUSTOMER_UPDATE_ERROR_MSG);
            }
        }
        else
        {
            if(addCustomer())
            {
                $msg = $this->getMessage(CUSTOMER_SAVE_SUCCESS_MSG);
            }
            else
            {
                $msg = $this->getMessage(CUSTOMER_SAVE_ERROR_MSG);
            }
        }
        
        setUserField('id',  '');
        setUserField('cmd', '');

        return $this->showEditor($msg);
    }

    /**
    * deletes user info
    * @return message
    */
    function deleteRecord()
    {
        $ID   = getUserField('id');

        $rows  = deleteCustomer($ID);

        if($rows)
        {
            $msg = $this->getMessage(CUSTOMER_DELETE_SUCCESS_MSG);
        }
        else
        {
            $msg = $this->getMessage(CUSTOMER_DELETE_ERROR_MSG);
        }

        setUserField('id',  '');
        setUserField('cmd', '');

        return $this->showNewEditor($msg);
    }

    /**
    * Shows user list
    * @return user list template
    */
    function showList()
    {
        $data['customer_name']         = getUserField('customer_name');
        $data['company_name_search']   = getUserField('company_name');
        $data['status']                = getUserField('status');
        $data['page_no']               = getUserField('page_no');
        $whereClause                   = '1';
        
        $data['page_no'] = $data['page_no'] ? $data['page_no'] : 0;
        
        $whereClause .= $data['customer_name'] ? ' AND CONCAT(CT.first_name, " ", CT.last_name) LIKE ' . q('%' . $data['customer_name'] . '%') : '';
        $whereClause .= $data['company_name_search'] ? ' AND CT.company_name  LIKE ' . q('%' . $data['company_name_search'] . '%') : '';
        $whereClause .= $data['status'] ? ' AND status = ' . q($data['status']) : '';
        
        $info['table']   = CUSTOMERS_TBL . ' AS CT LEFT JOIN ' . UK_CITY_TBL . ' AS UCT ON CT.county = UCT.id';
        $info['debug']   = false;
        $info['where']   = $whereClause . ' ORDER BY id LIMIT ' . $data['page_no']*ROWS_PER_PAGE . ', ' . ROWS_PER_PAGE;
        $info['fields']  = array('CT.*', 'UCT.city_name');
        $data['list']    = select($info);

        $data['status_list']         = getEnumFieldValues(CUSTOMERS_TBL, 'status');
        $data['row_count']           = getTotalRowCount($whereClause);
        $data['page_count']          = ceil ( $data['row_count']/ROWS_PER_PAGE );
        
        //dumpVar($data['row_count']);
        
        echo createPage(CUSTOMER_LIST_TEMPLATE, $data);
    }
} 
?>
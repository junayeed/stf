<?php
    /**
     * Filename: user_home.class.php
     * Purpose : Defines a UserHomeApp class which extends DefaultApplication.
     *           The derived class (defined here) is used to display
     *           appropriate user home page.
     *
     *
     * Developed by Softbiz Solution.
     * Version ID: $Id$
     */ 
   
    class UserHomeApp extends DefaultApplication 
    {
        /**
        * This is the "main" function which is called to run the application
        *
        * @param none
        * @return true if successful, else returns false
        */ 
        function run()
        {
            // Create a user object
            $thisUser = new User();
            

            // User must be authentiacted, else must login (again)
            if (!$thisUser->isAuthenticated())
            $thisUser->goLogin();

            // Load user info from session
            $thisUser->loadFromSession();
            getUserPhoto($thisUser->getPhotoID());
            
            // Get user type
            $userType = $thisUser->getUserType();
            
            // Get user home template
            $template = getHomeTemplate($userType); 
            
            if ($userType == 'Applicant')
            {
                header ('Location: /app/user_manager/user_manager.php');    
            }
            
            // Prepare smarty key=var
            $data = array();

            $contents = null;
            //$screen = $this->showLatestOrders();
            //$screen = $this->showDashBoard();

            // Set the current navigation item
            $this->setNavigation('home');

            echo $this->displayScreen($screen);

            return true;
        }
        
        function getUserPhoto($photoID)
        {
            $thisDoc  = new DocumentEntity($photoID);
            $fileName = $thisDoc->getRemoteFileName();

            $arr = explode('.', $fileName);

            $fileLocation            = '/documents/'.$fileName[0].'/'.$fileName[1].'/'.$photoID.'.'.$arr[1];
            $_SESSION['user_image']  = $fileLocation;
        }
        
        function showDashBoard()
        {
            $info['table']   = CUSTOMERS_TBL . ' AS CT LEFT JOIN ' . ORDER_DETAILS_TBL . ' AS ODT ON (CT.id = ODT.customer_id) ';
   	    $info['debug']   = false;
            $info['where']   = '1 GROUP BY ODT.customer_id ORDER BY total_sum DESC limit 10';
   	    $info['fields']  = array('ODT.customer_id','CT.company_name', 'SUM(ODT.total) AS total_sum');
   	  
   	    $data['year']           = getUserField('year');
            $data['top_customers']  = select($info);
   	    $data['yearly_income']  = $this->getYearlyIncome();
            $data['total_customer'] = $this->getTotalCustomers();
            $data['total_sale']     = $this->getTotalSale();
            $data['total_order']    = $this->getTotalOrders();
            $data['total_space']    = $this->getTotalSpace();
            $data['issue_month']    = $this->generateIssueMonth('String');
            
            //dumpVar($data);
            
            return createPage(DASHBOARD_TEMPLATE, $data);
        }
        
        function generateIssueMonth($format = 'Number')
        {
            $monthArray    = array('01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'May', '06' => 'Jun', 
                                   '07' => 'Jul', '08' => 'Aug', '09' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec');
            $current_year  = date('y');  // get the current year in '13' format
            $current_month = date('m');  // get the current month in 01/12 format 
            
            if ($current_month == 12)  // if the month is december make is 1401 that is January of next year
            {
                $current_month = '01';
                $current_year++;
            }
            else 
            {
                $current_month++;
            }
            
            if ($format == 'String')
            {
                return $monthArray[$current_month] . ' ' . $current_year;
            }
            
            return $current_year * 100 + $current_month;
        }
        
        function getTotalSpace()
        {
            $issue_month    = $this->generateIssueMonth();
            
            $info['table']  = ORDER_DETAILS_TBL . ' AS ODT LEFT JOIN ' . PRODUCT_TBL . ' AS PT ON (ODT.product_id = PT.id)';
            $info['debug']  = false;
            $info['where']  = '(CAST(start_month AS UNSIGNED) <= ' . $issue_month . ' AND CAST(end_month AS UNSIGNED) >= ' . $issue_month . ' ) OR ' . 
                              'CAST(start_month AS UNSIGNED) <= ' . $issue_month . ' AND end_month  = ' . q('Ongoing') . ' AND ' . 
                              'ODT.status = ' . q('B'); 
            $info['fields'] = array('ODT.start_month', 'ODT.end_month', 'ODT.alternative', 'ODT.qty', 'ODT.status', 'PT.qty_per_unit');
            
            $result = select($info);
            //dumpVar($result);
            
            if ($result)
            {
                foreach($result as $key => $value)
                {
                    if ( !$this->isOrderShowable($value, $issue_month))
                    {
                        continue;
                    }
                    else
                    {
                        $total_space += $value->qty*$value->qty_per_unit;
                    }
                }
            }
            
            //dumpVar($data);
            return $total_space;
        }
        
        function getTotalOrders()
        {
            $info['table']  = ORDER_DETAILS_TBL;
            $info['debug']  = false;
            $info['fields'] = array('COUNT(id) AS total_order');
            
            $result = select($info);
            
            return $result[0]->total_order;
        }
        
        function getTotalSale()
        {
            $info['table']  = ORDER_DETAILS_TBL;
            $info['debug']  = false;
            $info['fields'] = array('SUM(total) AS total_sale');
            
            $result = select($info);
            
            return $result[0]->total_sale;
        }
        
        function getTotalCustomers()
        {
            $info['table']  = CUSTOMERS_TBL;
            $info['debug']  = false;
            $info['fields'] = array('COUNT(id) as total_customer');
            
            $result = select($info);
            
            return $result[0]->total_customer;
        }
        
        function getYearlyIncome()
        {
            $current_year  = date('y');
            $start_year    = $current_year * 100;
            $end_year      = $start_year + 12;
            $monthArray    = array('01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'May', '06' => 'Jun', 
                                   '07' => 'Jul', '08' => 'Aug', '09' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec');
            
            $info['table']  = ORDER_DETAILS_TBL . ' AS ODT LEFT JOIN ' . PRODUCT_TBL . ' AS PT ON (ODT.product_id = PT.id)';
            $info['debug']  = false;
            $info['where']  = 'CAST(start_month AS UNSIGNED) > ' . $start_year . ' AND CAST(start_month AS UNSIGNED) <= ' . $end_year; 
            $info['fields'] = array('ODT.total', 'ODT.start_month', 'ODT.end_month', 'ODT.alternative', 'ODT.qty', 'ODT.status', 'PT.qty_per_unit');
            
            $result = select($info);
            //dumpVar($result);
            
            if ($result)
            {
                foreach($result as $key => $value)
                {
                    foreach($monthArray as $monthKey => $monthValue)
                    {
                        if ( !$this->isOrderShowable($value, $current_year.$monthKey))
                        {
                        continue;
                        }

                        $array_index = $current_year.$monthKey;
                        $data[$array_index]['total'] += $value->total;
                        $data[$array_index]['month']  = $monthValue;
                    }
                }
            }
            
            ksort($data); // sort the list according to $key
            //dumpVar($data);
            return $data;
        }
        
        function isOrderShowable($order, $issue_month)
        {

            if ($order->alternative == 'Yes')  // if the order is set to alternate month
            {
                if ($order->end_month != 'Ongoing')
                {    
                    if( ($order->start_month % 2 == $issue_month % 2) && ($issue_month >= $order->start_month && $issue_month <= $order->end_month) )
                    {
                        //echo("Start Month = " . $order->start_month . " End Month = " . $order->end_month . " Issue Month = " . $issue_month . " Alternative = " . $order->alternative . ' ORDER ID= ' . $order->id);
                        return true;
                    }
                    else
                    {
                        return false;
                    }
                }
                else
                {
                    if( ($order->start_month % 2 == $issue_month % 2) && ($order->start_month <= $issue_month) )
                    {
                        //echo("Start Month = " . $order->start_month . " End Month = " . $order->end_month . " Issue Month = " . $issue_month . " Alternative = " . $order->alternative . ' ORDER ID= ' . $order->id);
                        return true;
                    }
                    else 
                    {
                        return false;
                    }

                }
            }
            else   // else the order is not set to alternate month just check whether the issue month is within the range of start and end month
            {
                if ($order->end_month == 'Ongoing' && $order->start_month <= $issue_month)
                {
                    return true;
                }
                else
                {
                    if ($issue_month >= $order->start_month && $issue_month <= $order->end_month)
                    {
                        return true;
                    }
                    else
                    {
                        return false;
                    }
                }

            }

            return false;
        }
        
        function convertNumber2Month($monthNumber)
        {
            $monthArray = array('01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'May', '06' => 'Jun', 
                                '07' => 'Jul', '08' => 'Aug', '09' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec');
            if ($monthNumber == 'Ongoing')
            {
                return $monthNumber;
            }

            //$monthArray = array('01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'May', '06' => 'Jun', '07' => 'Jul', '08' => 'Aug', '09' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec');
            $month      = substr($monthNumber, 2, 2);
            $year       = substr($monthNumber, 0, 2);

            if ($month && $year)
            {
                return $monthArray[$month] . ' ' . $year;
            }

            return '';
        }
      
        function showLatestOrders()
        {
            $monthArray = array('1' => 'Jan', '2' => 'Feb', '3' => 'Mar', '4' => 'Apr', '5' => 'May', '6' => 'Jun',
                                '7' => 'Jul', '8' => 'Aug', '9' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec');
            
            $info['table']   = CUSTOMERS_TBL . ' AS CT LEFT JOIN ' . ORDER_DETAILS_TBL . ' AS ODT ON (CT.id = ODT.customer_id) LEFT JOIN ' . PRODUCT_TBL . ' AS PT ON (ODT.product_id = PT.id) LEFT JOIN ' . MAGAZINES_TBL . ' AS MT ON (ODT.magazine_code = MT.id)' ;
   	    $info['debug']   = false;
            $info['where']   = '1 ORDER BY ODT.id DESC';
   	    $info['fields']  = array('ODT.id', 'CT.id AS customer_id', 'CT.company_name', 'PT.product_code', 'PT.description', 
                                     'MT.magazine_abvr', 'ODT.start_month', 'ODT.end_month', 'ODT.alternative',
                                     'ODT.page', 'ODT.qty', 'ODT.unit_price', 'ODT.discount', 'ODT.total', 'ODT.status');
   	  
   	    $data['order_list'] = select($info);
            
            foreach($data['order_list'] as $key => $value)
            {
                $start_month = str_replace('0', '', substr($value->start_month, 2, 2));
                $start_year  = str_replace('0', '', substr($value->start_month, 0, 2));
                
                $value->start_month = $monthArray[$start_month] . ' ' . $start_year;
                
                if ( $value->end_month != 'Ongoing')
                {
                    $end_month = str_replace('0', '', substr($value->end_month, 2, 2));
                    $end_year  = str_replace('0', '', substr($value->end_month, 0, 2));
                    
                    $value->end_month = $monthArray[$end_month] . ' ' . $end_year;
                }
            }
            
            return createPage(DASHBOARD_TEMPLATE, $data);
        }
    }
?>
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
            $screen = $this->showDashBoard();

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
            $data['total_applicant'] = $this->getTotalApplicants();
            $data['gender']          = $this->getTotalGender();
            $data['country_list']    = $this->getTotalApplicantsByCountry();
            
            return createPage(DASHBOARD_TEMPLATE, $data);
        }
        
        function getTotalApplicants()
        {
            $info['table']  = APPLICATIONS_TBL;
            $info['debug']  = false;
            $info['fields'] = array('COUNT(id) AS total_applicant');
            $info['where']  = 'application_status != ' . q('Not Submitted');
            
            $result = select($info);
            
            return $result[0]->total_applicant;
        }
        
        function getTotalGender()
        {
            $info['table']  = APPLICATIONS_TBL . ' AS AT LEFT JOIN ' . USER_TBL . ' AS UT ON (AT.uid = UT.uid)';
            $info['debug']  = false;
            $info['fields'] = array('SUM(IF(gender = "male", 1,0)) AS `male`', 'SUM(IF(gender = "female", 1,0)) AS `female`', 'COUNT(gender) AS `total`');
            $info['where']  = 'application_status != ' . q('Not Submitted');
            
            $result = select($info);
            
            return $result[0];
        }
        
        function getTotalApplicantsByCountry()
        {
            $info['table']  = APPLICATIONS_TBL . ' AS AT LEFT JOIN ' . COUNTRY_LOOKUP_TBL . ' AS CLT ON (AT.country = CLT.id)';
            $info['debug']  = false;
            $info['fields'] = array('CLT.id as country , COUNT(AT.country) as total');
            $info['where']  = 'application_status != ' . q('Not Submitted') . '  group by country order by country';
            
            $result = select($info);
            
            foreach($result as $key => $value)
            {
                $retData[$value->country] = $value->total;
            }
            
            return $retData;
        }
    }
?>
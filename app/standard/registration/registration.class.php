<?php
/**
 * File: registration.class.php
 * This application is used to authenticate users
 *
 * @package LoginApp
 * @author  php@softbizsolution.com
 * @version $Id$
 *
 */

class RegistrationApp extends DefaultApplication
{
   /**
   * This is the "main" function which is called to run the application
   *
   * @param none
   * @return true if successful, else returns false
   */
   function run()
   {
       $cmd = getUserField('cmd');  
      
      switch ($cmd)
      {
           case 'checkuser'  : $screen = $this->isUsernameExists(); break;
           case 'checkemail' : $screen = $this->isEmailExists(); break;
      }

      // Set the current navigation item
      $this->setNavigation('user');
      
      if ($cmd == 'checkuser' || $cmd == 'checkemail')
      {
          return;
      }

      $credentials = array();

      // Get the user supplied credentials
      $credentials[LOGIN_ID_FIELD]  = getUserField('loginid');
      $credentials['password']      = getUserField('password');

      // Create a new user object with the credentials
      $thisUser = new User($credentials);

      // Authenticate the user
      $ok = $thisUser->authenticate();

      // If successful (i.e. user supplied valid credentials)
      // show user home
      if ($ok)
      {
          $thisUser->goHome();
      }
      // User supplied invalid credentials so show login form
      // again
      else
      {
          $data = array();
          $data = array_merge($_REQUEST, $data);
          echo createPage(REGISTRATION_TEMPLATE, $data);
      }

      return true;
   }
   
   function isUsernameExists()
   {
       $username = getUserField('username');
       
       $info['table'] = USER_TBL;
       $info['debug'] = false;
       $info['where'] = 'username = ' . q($username);
       
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
   
   function isEmailExists()
   {
       $email = getUserField('email');
       
       $info['table'] = USER_TBL;
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
} // End class

?>
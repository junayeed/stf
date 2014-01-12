<?php
/**
 * File: login.class.php
 * This application is used to authenticate users
 *
 * @package LoginApp
 * @author  php@softbizsolution.com
 * @version $Id$
 *
 */

class LoginApp extends DefaultApplication
{
   /**
   * This is the "main" function which is called to run the application
   *
   * @param none
   * @return true if successful, else returns false
   */
   function run()
   {
      $credentials = array();


      // Get the user supplied credentials
      $credentials[LOGIN_ID_FIELD]  = getUserField('loginid');
      $credentials['password']      = getUserField('password_login');

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
          echo createPage(LOGIN_TEMPLATE, $data);
      }

      return true;
   }
} // End class

?>
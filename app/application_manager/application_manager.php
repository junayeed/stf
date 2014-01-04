<?php
/**
 * This is the application file that is invoked to
 * instantiate the application manager application
 */

   // include the main configuration file
   require_once($_SERVER['DOCUMENT_ROOT'] .'/app/common/conf/main.conf.php');
   require_once(LOCAL_CONFIG_DIR          .'/dp.conf.php');
   require_once(LOCAL_LIB_DIR             .'/dp.lib.php');

   // Instantiate the application manager class
   $thisApp  = new applicationManagerApp();

   // Instanciate the user class
   $thisUser = new User();

   // checks the user authentication
   if($thisUser->isAuthenticated())
   {
      $thisApp->run();
   }
   else
   {
      $thisUser->goLogin();
   }

?>
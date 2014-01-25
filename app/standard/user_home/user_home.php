<?php
   /**
    * Filename: user_home.php
    * Purpose : This application displays user home page
    *
    */
   
   // Load main configuration file
   require_once($_SERVER['DOCUMENT_ROOT'] . '/app/common/conf/main.conf.php');
   
   require_once(LOCAL_CONFIG_DIR          .'/dp.conf.php');
   require_once(LOCAL_LIB_DIR             .'/dp.lib.php');
   
   // Create an instance of the UserHomeApp object
   $thisApp = new UserHomeApp();

   // Run the application
   $thisApp->run();
      
?>
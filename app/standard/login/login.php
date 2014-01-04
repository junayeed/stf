<?php


   require_once($_SERVER['DOCUMENT_ROOT'] . '/app/common/conf/main.conf.php');
   
   // Create a login application object
   $thisApp = new LoginApp();
   
   // Run the application
   $thisApp->run();
   
?>
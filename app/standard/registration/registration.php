<?php


   require_once($_SERVER['DOCUMENT_ROOT'] . '/app/common/conf/main.conf.php');
   
   // Create a registration application object
   $thisApp = new RegistrationApp();
   
   // Run the application
   $thisApp->run();
   
?>
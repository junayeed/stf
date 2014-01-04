<?php
   /**
    * Filename: user_home.php
    * Purpose : This application displays user home page
    *
    * Developed by EVOKNOW, Inc.
    * Copyright (c) 2005 EVOKNOW, Inc.
    * Version ID: $Id$
    */
   
   // Load main configuration file
   require_once($_SERVER['DOCUMENT_ROOT'] . '/app/common/conf/main.conf.php');
   
   // Create an instance of the UserHomeApp object
   $thisApp = new UserHomeApp();

   // Run the application
   $thisApp->run();
      
?>
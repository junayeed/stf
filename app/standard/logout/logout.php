<?php
   /**
    * Filename: login.php
    * Purpose : This application logs out already logged in users
    *
    * Developed by EVOKNOW, Inc.
    * Copyright (c) 2005 EVOKNOW, Inc.
    * Version ID: $Id$
    */
   
   // Require main configuration file. 
   require_once($_SERVER['DOCUMENT_ROOT'] . '/app/common/conf/main.conf.php');
   
   // Create an instance of a logout application object
   $thisApp = new LogoutApp();
   
   // Run the application
   $thisApp->run();
   
   
?>
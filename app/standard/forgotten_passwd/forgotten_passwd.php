<?php
  /*
   * Filename: forgeotten_password.php
   * Purpose : This application performs retrieval of forgotten password
   *
   * Developed by EVOKNOW, Inc.
   * Copyright (c) 2005 EVOKNOW, Inc.
   * Version ID: $Id$
   */
   
   require_once($_SERVER['DOCUMENT_ROOT'] . '/app/common/conf/main.conf.php');
   require_once(USER_CLASS);
   
   //Instantiate the  ForgottonPasswordApp Class
   $thisApp = new ForgottonPasswordApp();
   
     
   $thisApp->run();
   
?>
<?php
   /*******************************************************
    *  File name: database.conf.php
    *
    *  Purpose: this file is used to store database
    *           table name constants and it also starts
    *           the database connection
    *
    *  CVS ID: $Id$
    *
    ********************************************************/

   // If main configuration file which defines VERSION constant
   // is not loaded, die!
   if (! defined('VERSION'))
   {
      echo "You cannot access this file directly!";
      die();
   }

  // Please note:
  // in production mode, the database authentication information
  // may vary.
  if (PRODUCTION_MODE)
  {
     define('DB_USER', 'root');
     define('DB_PASS', 'root123');
     define('DB_NAME', 'stf');
     define('DB_HOST', 'localhost');
  }
  else
  {
    define('DB_USER', 'root');
    define('DB_PASS', '');

    define('DB_NAME', 'stf');
    define('DB_HOST', '127.0.0.1');
  }

  /**
  * Common Table Constant
  */
  // Common Tables
  define('APP_INFO_TBL',                 DB_NAME . '.app_info');
  define('APP_LANGUAGE_TBL',             DB_NAME . '.app_language');
  define('APP_MESSAGE_TBL',              DB_NAME . '.app_message');
  define('APP_META_TBL',                 DB_NAME . '.app_meta');
  define('APP_PROFILE_TBL',              DB_NAME . '.app_profile');

  define('COUNTRY_LOOKUP_TBL',           DB_NAME . '.country_lookup');
  define('CITY_LOOKUP_TBL',              DB_NAME . '.city_lookup');
  define('INT_CALLING_CODE_LOOKUP_TBL',  DB_NAME . '.int_calling_code_lookup');

  define('DOCUMENT_TBL',                 DB_NAME . '.document');

  define('USER_TBL',                     DB_NAME . '.user');
  define('USER_ADDRESS_TBL',             DB_NAME . '.user_address');
  
  define('GUARDIAN_TBL',                 DB_NAME . '.guardians');
  define('APPLICATIONS_TBL',             DB_NAME . '.applications');
  define('SESSIONS_TBL',                 DB_NAME . '.sessions');
  define('TICKETS_TBL',                  DB_NAME . '.tickets');
  define('ACADEMIC_QUALIFICATIONS_TBL',  DB_NAME . '.academic_qualifications');
  define('AIRFARES_TBL',                 DB_NAME . '.airfares');
 
  
  if (AUTO_CONNECT_TO_DATABASE)
  {
      $dbcon = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die("Could not connect: " . mysql_error());
      mysql_select_db(DB_NAME, $dbcon) or die("Could not find: " . mysql_error());
  }

?>
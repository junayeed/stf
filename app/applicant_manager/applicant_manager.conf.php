<?php

/**
 * File: application_manager.conf.php
 * This is the configuration file for the application manager application
 *
 */

   // include the user class
   require_once(USER_CLASS);
   require_once(DOCUMENT_CLASS);


   /**#@+
   * Template PATH Constant
   */
   define('TEMPLATE_DIR',                  APP_CONTENTS_DIR     . '/' . CURRENT_APP_PREFIX);
   define('REL_TEMPLATE_DIR',              REL_APP_CONTENTS_DIR . '/' . CURRENT_APP_PREFIX);

   /**#@+
   * Template Constant
   */
   define('APPLICANT_EDITOR_TEMPLATE',          TEMPLATE_DIR . '/applicant_manager.html');
   define('APPLICANT_LIST_TEMPLATE',            TEMPLATE_DIR . '/applicant_list.html');
   define('APPLICANT_DETAILS_TEMPLATE',         TEMPLATE_DIR . '/applicant_details.html');
   define('ACCEPTED_EMAIL_TEMPLATE',            TEMPLATE_DIR . '/email_template.html');


   /**#@+
   * Application Constant
   */
   define('REL_DOCUMENT_DIR',              '/documents');
   define('IMG_WIDTH',                     170);
   define('IMG_HEIGHT',                    190);

   /**#@+
   * Message Constant
   */
   define('USER_SAVE_SUCCESS_MSG',         1011);
   define('USER_UPDATE_SUCCESS_MSG',       1012);
   define('USER_DELETE_SUCCESS_MSG',       1013);
   define('USER_SAVE_ERROR_MSG',           1021);
   define('USER_UPDATE_ERROR_MSG',         1022);
   define('USER_DELETE_ERROR_MSG',         1023);
   define('DUPLICATE_USERNAME',            1031);
?>
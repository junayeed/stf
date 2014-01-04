<?php

/**
 * File: artwork_manager.conf.php
 * This is the configuration file for the artwork manager application
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
   define('ARTWORK_EDITOR_TEMPLATE',          TEMPLATE_DIR . '/artwork_manager.html');
   define('ARTWORK_LIST_TEMPLATE',            TEMPLATE_DIR . '/artwork_list.html');


   define('ADDED',                         'added');
   define('DELETED',                       'deleted');
   define('EDITED',                        'edited');
   
   /**#@+
   * Message Constant
   */
   define('ORDER_SAVE_SUCCESS_MSG',         4011);
   define('ORDER_UPDATE_SUCCESS_MSG',       4012);
   define('ORDER_DELETE_SUCCESS_MSG',       4013);
   define('ORDER_SAVE_ERROR_MSG',           4014);
   define('ORDER_UPDATE_ERROR_MSG',         4015);
   define('ORDER_DELETE_ERROR_MSG',         4016);
?>
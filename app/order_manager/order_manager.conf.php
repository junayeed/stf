<?php

/**
 * File: order_manager.conf.php
 * This is the configuration file for the order manager application
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
   define('ORDER_EDITOR_TEMPLATE',          TEMPLATE_DIR . '/order_manager.html');
   define('ORDER_LIST_TEMPLATE',            TEMPLATE_DIR . '/order_list.html');
   define('PRODUCT_LIST_TEMPLATE',          TEMPLATE_DIR . '/product_list.html');

   define('ROWS_PER_PAGE',         4);
   /**#@+
   * Application Constant
   */
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
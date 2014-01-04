<?php

    /**
    * File: product_manager.conf.php
    * This is the configuration file for the product manager application
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
    define('CUSTOMER_EDITOR_TEMPLATE',          TEMPLATE_DIR . '/customer_manager.html');
    define('CUSTOMER_LIST_TEMPLATE',            TEMPLATE_DIR . '/customer_list.html');
    
    define('ROWS_PER_PAGE',         15);
    /**#@+
    * Message Constant
    */
    define('CUSTOMER_SAVE_ERROR_MSG',       3000);
    define('CUSTOMER_UPDATE_ERROR_MSG',     3001);
    define('CUSTOMER_SAVE_SUCCESS_MSG',     3002);
    define('CUSTOMER_UPDATE_SUCCESS_MSG',   3003);
    define('CUSTOMER_DELETE_SUCCESS_MSG',   3004);
    define('CUSTOMER_DELETE_ERROR_MSG',     3005);
?>
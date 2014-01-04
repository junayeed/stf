<?php

    /**
    * File: magazine_manager.conf.php
    * This is the configuration file for the user manager application
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
    define('PRODUCT_EDITOR_TEMPLATE',          TEMPLATE_DIR . '/product_manager.html');
    define('PRODUCT_LIST_TEMPLATE',            TEMPLATE_DIR . '/product_list.html');
    
    define('ROWS_PER_PAGE',         15);
    define('PRODUCT_ARCHIVE',       'Archive');

    /**#@+
    * Message Constant
    */
    define('PRODUCT_ERROR_MSG',            2500);
    define('PRODUCT_UPDATE_ERROR_MSG',     2501);
    define('PRODUCT_DELETE_ERROR_MSG',     2505);
    define('PRODUCT_SAVE_SUCCESS_MSG',     2502);
    define('PRODUCT_UPDATE_SUCCESS_MSG',   2503);
    define('PRODUCT_DELETE_SUCCESS_MSG',   2504);
?>
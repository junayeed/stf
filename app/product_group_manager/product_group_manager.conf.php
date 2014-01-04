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
    define('PRODUCT_GROUP_EDITOR_TEMPLATE',          TEMPLATE_DIR . '/product_group_manager.html');
    define('PRODUCT_GROUP_LIST_TEMPLATE',            TEMPLATE_DIR . '/product_group_list.html');

    /**#@+
    * Message Constant
    */
    define('PRODUCT_GROUP_SAVE_ERROR_MSG',       2100);
    define('PRODUCT_GROUP_UPDATE_ERROR_MSG',     2101);
    define('PRODUCT_GROUP_DELETE_ERROR_MSG',     2105);
    define('PRODUCT_GROUP_SAVE_SUCCESS_MSG',     2102);
    define('PRODUCT_GROUP_UPDATE_SUCCESS_MSG',   2103);
    define('PRODUCT_GROUP_DELETE_SUCCESS_MSG',   2104);
?>
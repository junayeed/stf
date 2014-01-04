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
    define('MAGAZINE_EDITOR_TEMPLATE',          TEMPLATE_DIR . '/magazine_manager.html');
    define('MAGAZINE_LIST_TEMPLATE',            TEMPLATE_DIR . '/magazine_list.html');
    
    define('ROWS_PER_PAGE',         10);

    /**#@+
    * Message Constant
    */
    define('MAGAZINE_SAVE_ERROR_MSG',       2000);
    define('MAGAZINE_UPDATE_ERROR_MSG',     2001);
    define('MAGAZINE_DELETE_ERROR_MSG',     2005);
    define('MAGAZINE_SAVE_SUCCESS_MSG',     2002);
    define('MAGAZINE_UPDATE_SUCCESS_MSG',   2003);
    define('MAGAZINE_DELETE_SUCCESS_MSG',   2004);
?>
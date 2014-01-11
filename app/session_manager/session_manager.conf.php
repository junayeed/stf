<?php

    /**
    * File: session_manager.conf.php
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
    define('SESSION_EDITOR_TEMPLATE',          TEMPLATE_DIR . '/session_manager.html');
    define('SESSION_LIST_TEMPLATE',            TEMPLATE_DIR . '/session_list.html');
    
    define('ROWS_PER_PAGE',         10);

    /**#@+
    * Message Constant
    */
    define('SESSION_SAVE_ERROR_MSG',       2000);
    define('SESSION_UPDATE_ERROR_MSG',     2001);
    define('SESSION_DELETE_ERROR_MSG',     2005);
    define('SESSION_SAVE_SUCCESS_MSG',     2002);
    define('SESSION_UPDATE_SUCCESS_MSG',   2003);
    define('SESSION_DELETE_SUCCESS_MSG',   2004);
?>
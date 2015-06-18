<?php

    /**
    * File: arifare_manager.conf.php
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
    define('REPORT_EDITOR_TEMPLATE',          TEMPLATE_DIR . '/report_manager.html');
    define('REPORT_PDF_TEMPLATE',             TEMPLATE_DIR . '/report_manager_pdf.html');
    
    define('ROWS_PER_PAGE',         10);
?>
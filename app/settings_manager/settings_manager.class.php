<?php

/**
 * File: rsettings_manager.class.php
 *
 */

/**
 * The settingsManagerApp application class
 */

class settingsManagerApp extends DefaultApplication
{
    function run()
    {
        $cmd = getUserField('cmd');

        switch ($cmd)
        {
            default : $screen = $this->showEditor($msg);     break;
        }

        // Set the current navigation item
        $this->setNavigation('user');

        if ($cmd == 'list')
        {
            echo $screen;
        }
        else
        {
            echo $this->displayScreen($screen);
        }

        return true;
    }

    /**
    * Shows User Editor
    * @param message
    * @return user editor template
    */
    function showEditor($msg)
    {
        return createPage(SETTINGS_EDITOR_TEMPLATE, $data);
    }
}   
?>
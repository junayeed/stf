<?php

/**
 * File: magazine_manager.class.php
 *
 */

/**
 * The magazineManagerApp application class
 */

class magazineManagerApp extends DefaultApplication
{
   /**
   * Constructor
   * @return true
   */

   function run()
   {
      $cmd = getUserField('cmd');  
      
      switch ($cmd)
      {
           case 'edit'         : $screen = $this->showEditor($msg);     break;
           case 'new'          : $screen = $this->showNewEditor($msg);  break;
           case 'add'          : $screen = $this->saveRecord();         break;
           case 'delete'       : $screen = $this->deleteRecord();       break;
           case 'list'         : $screen = $this->showList();           break;
           default             : $screen = $this->showEditor($msg);     break;
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
    function showNewEditor($msg)
    {
        $data['message'] = $msg;
        
        return createPage(MAGAZINE_EDITOR_TEMPLATE, $data);
    }

    /**
    * Shows User Editor
    * @param message
    * @return user editor template
    */
    function showEditor($msg)
    {
        $id = getUserField('id');

        if (!empty($id))
        {
            $thisMag = getMagazineInfo($id);

            if( empty($thisMag))
            {
                $thisMag = array();
            }

            foreach($thisMag as $key => $value)
            {
                $magData[$key] = $value;	
            }

            $data = array_merge(array(), $magData);
        }

        $data['message'] = $msg;

        return createPage(MAGAZINE_EDITOR_TEMPLATE, $data);
    }

    /**
    * Saves User information
    * @return message
    */
    function saveRecord()
    {
        $ID  = getUserField('id');

        if($ID)
        {
            if(updateMagazine($ID))
            {
                $msg = $this->getMessage(MAGAZINE_UPDATE_SUCCESS_MSG);
            }
            else
            {
                $msg = $this->getMessage(MAGAZINE_UPDATE_ERROR_MSG);
            }
        }
        else
        {
            if(addMagazine())
            {
                $msg = $this->getMessage(MAGAZINE_SAVE_SUCCESS_MSG);
            }
            else
            {
                $msg = $this->getMessage(MAGAZINE_SAVE_ERROR_MSG);
            }
        }
        
        setUserField('id',  '');
        setUserField('cmd', '');

        return $this->showEditor($msg);
    }

    /**
    * deletes user info
    * @return message
    */
    function deleteRecord()
    {
        $ID   = getUserField('id');

        $rows  = deleteMagazine($ID);

        if($rows)
        {
            $msg = $this->getMessage(MAGAZINE_DELETE_SUCCESS_MSG);
        }
        else
        {
            $msg = $this->getMessage(MAGAZINE_DELETE_ERROR_MSG);
        }

        setUserField('id',  '');
        setUserField('cmd', '');

        return $this->showNewEditor($msg);
    }

    /**
    * Shows user list
    * @return user list template
    */
    function showList()
    {
        $data['page_no'] = getUserField('page_no');
        $data['page_no'] = $data['page_no'] ? $data['page_no'] : 0;
        
        $info['table']  = MAGAZINES_TBL;
        $info['debug']  = false;
        $info['where']  = '1 ORDER BY magazine_abvr ASC LIMIT ' . $data['page_no']*ROWS_PER_PAGE . ', ' . ROWS_PER_PAGE;
        $data['list']   = select($info);
        
        $data['row_count']    = getTotalRowCount($whereClause);
        $data['page_count']   = ceil ( $data['row_count']/ROWS_PER_PAGE );

        //dumpVar($data);

        echo createPage(MAGAZINE_LIST_TEMPLATE, $data);
    }
} 
?>
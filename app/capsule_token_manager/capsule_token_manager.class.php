<?php

/**
 * File: capsule_token_manager.class.php
 *
 */

/**
 * The capsuleTokenManagerApp application class
 */

class capsuleTokenManagerApp extends DefaultApplication
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
        //$id = getUserField('id');

        //if (!empty($id))
        //{
        $thisToken = getCapsuleToken();

        
        if( empty($thisToken))
        {
            $thisToken = array();
        }
        else
        {
            foreach($thisToken as $key => $value)
            {
                $tokenData[$key] = $value;	
            }

            $data = array_merge(array(), $tokenData);
        }
            
        return createPage(CAPSULE_TOKEN_EDITOR_TEMPLATE, $data);
    }   

    /**
    * Saves User information
    * @return message
    */
    function saveRecord()
    {
        $ID    = getUserField('id');
        $data  = getUserDataSet(CAPSULE_TOKEN_TBL);
        
        $info['table']  = CAPSULE_TOKEN_TBL;
        $info['debug']  = false;
        $info['data']   = $data;

        if($ID)
        {
            $info['where']   = 'id = ' . $ID;
            update($info);
        }
        else
        {
            insert($info);
        }
        
        return $this->showEditor($msg);
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
        $info['where']  = '1 ORDER BY id LIMIT ' . $data['page_no']*ROWS_PER_PAGE . ', ' . ROWS_PER_PAGE;
        $data['list']   = select($info);
        
        $data['row_count']    = getTotalRowCount($whereClause);
        $data['page_count']   = ceil ( $data['row_count']/ROWS_PER_PAGE );

        //dumpVar($data);

        echo createPage(CAPSULE_TOKEN_LIST_TEMPLATE, $data);
    }
} 
?>
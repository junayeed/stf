<?php

/**
 * File: product_group_manager.class.php
 *
 */

/**
 * The productGroupManagerApp application class
 */

class productGroupManagerApp extends DefaultApplication
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
      $this->setNavigation('product_group');
      
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
        
        return createPage(PRODUCT_GROUP_EDITOR_TEMPLATE, $data);
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
            $thisMag = getProductGroupInfo($id);

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

        return createPage(PRODUCT_GROUP_EDITOR_TEMPLATE, $data);
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
            if(updateProductGroup($ID))
            {
                $msg = $this->getMessage(PRODUCT_GROUP_UPDATE_SUCCESS_MSG);
            }
            else
            {
                $msg = $this->getMessage(PRODUCT_GROUP_UPDATE_ERROR_MSG);
            }
        }
        else
        {
            if(addProductGroup())
            {
                $msg = $this->getMessage(PRODUCT_GROUP_SAVE_SUCCESS_MSG);
            }
            else
            {
                $msg = $this->getMessage(PRODUCT_GROUP_SAVE_ERROR_MSG);
            }
        }

        return $this->showEditor($msg);
    }

    /**
    * deletes user info
    * @return message
    */
    function deleteRecord()
    {
        $ID   = getUserField('id');

        $rows  = deleteProductGroup($ID);

        if($rows)
        {
            $msg = $this->getMessage(PRODUCT_GROUP_DELETE_SUCCESS_MSG);
        }
        else
        {
            $msg = $this->getMessage(PRODUCT_GROUP_DELETE_ERROR_MSG);
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
        $info['table']  = PRODUCT_GROUP_TBL;
        $info['debug']  = false;
        $data['list']   = select($info);

        //dumpVar($data);

        echo createPage(PRODUCT_GROUP_LIST_TEMPLATE, $data);
    }
} 
?>
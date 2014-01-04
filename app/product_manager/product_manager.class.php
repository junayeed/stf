<?php

/**
 * File: product_manager.class.php
 *
 */

/**
 * The productManagerApp application class
 */

class productManagerApp extends DefaultApplication
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
           case 'edit'           : $screen = $this->showEditor($msg);             break;
           case 'new'            : $screen = $this->showNewEditor($msg);          break;
           case 'add'            : $screen = $this->saveRecord();                 break;
           case 'delete'         : $screen = $this->deleteRecord();               break;
           case 'list'           : $screen = $this->showList();                   break;
           case 'chkproductcode' : $screen = $this->checkDuplicateProductCode();  break;
           default               : $screen = $this->showEditor($msg);             break;
      }

      // Set the current navigation item
      $this->setNavigation('product_group');
      
      if ($cmd == 'chkproductcode')
          return;
      
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
   
    function checkDuplicateProductCode()
    {
        $product_code = getUserField('product_code');

        $info['table'] = PRODUCT_TBL;
        $info['debug'] = false;
        $info['where'] = 'product_code = ' . q($product_code);

        $result = select($info);

        if ( !empty($result) )
        {
            echo json_encode('1');
        }
        else
        {
            echo json_encode('');
        }
    }   
   
    /**
    * Shows User Editor
    * @param message
    * @return user editor template
    */
    function showNewEditor($msg)
    {
        $data['message']             = $msg;
        $data['magazine_code_list']  = getMagazineCode();
        $data['product_group_list']  = getProductGroupList();
        
        return createPage(PRODUCT_EDITOR_TEMPLATE, $data);
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
            $thisMag = getProductDetails($id);

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

        $data['message']              = $msg;
        $data['magazine_code_list']   = getMagazineCode();
        $data['product_group_list']   = getProductGroupList();
        $data['product_size_list']    = getProductSizeList();
        $data['product_option_list']  = getEnumFieldValues(PRODUCT_TBL, 'product_option');
        

        return createPage(PRODUCT_EDITOR_TEMPLATE, $data);
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
                $msg = $this->getMessage(PRODUCT_UPDATE_SUCCESS_MSG);
            }
            else
            {
                $msg = $this->getMessage(PRODUCT_UPDATE_ERROR_MSG);
            }
        }
        else
        {
            if(addProductGroup())
            {
                $msg = $this->getMessage(PRODUCT_SAVE_SUCCESS_MSG);
            }
            else
            {
                $msg = $this->getMessage(PRODUCT_SAVE_ERROR_MSG);
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

        $rows  = deleteProduct($ID);

        if($rows)
        {
            $msg = $this->getMessage(PRODUCT_DELETE_SUCCESS_MSG);
        }
        else
        {
            $msg = $this->getMessage(PRODUCT_DELETE_ERROR_MSG);
        }

        setUserField('id',  '');
        setUserField('cmd', '');

        return $this->showEditor($msg);
    }

    /**
    * Shows user list
    * @return user list template
    */
    function showList()
    {
        $data['magazine_code']    = getUserField('magazine_code');
        $data['product_group']    = getUserField('product_group');
        $data['product_size']     = getUserField('product_size');
        $data['options']          = getUserField('options');
        $data['page_no']          = getUserField('page_no');
        $whereClause              = '1';
        
        $data['page_no'] = $data['page_no'] ? $data['page_no'] : 0;
        
        $whereClause .= ( $data['options'] ) ? ' AND product_option = ' . q($data['options']) : '';
        if ($data['magazine_code'])
        {
            $whereClause .= ' AND PT.magazine_code = ' . $data['magazine_code'];
        }
        
        if ($data['product_group'])
        {
            $whereClause .= ' AND PT.product_group = ' . $data['product_group'];
        }
        
        if ( $data['product_size'] || $data['product_size'] == '0')
        {
            $whereClause .= ' AND PT.qty_per_unit = ' . $_REQUEST['product_size'];
        }
        
        
        $info['table']   = PRODUCT_TBL . ' AS PT LEFT JOIN ' . MAGAZINES_TBL . ' AS MT ON PT.magazine_code = MT.id LEFT JOIN ' . PRODUCT_GROUP_TBL . ' AS PGT ON PT.product_group = PGT.id';
        $info['debug']   = false;
        $info['where']   = $whereClause . ' ORDER BY PT.product_code LIMIT ' . $data['page_no']*ROWS_PER_PAGE . ', ' . ROWS_PER_PAGE;
        $info['fields']  = array('PT.*', 'MT.magazine_abvr', 'PGT.product_group');
        $data['list']    = select($info);

        $data['magazine_code_list']  = getMagazineCode();
        $data['product_group_list']  = getProductGroupList();
        $data['row_count']           = getTotalRowCount($whereClause);
        $data['page_count']          = ceil ( $data['row_count']/ROWS_PER_PAGE );
        
        //dumpVar($data['row_count']);
        
        echo createPage(PRODUCT_LIST_TEMPLATE, $data);
    }
} 
?>
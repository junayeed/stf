<?php
  /**
   * File: dp.lib.php
   * Library File
   *
   * Version ID: $Id$
   */

   /**
   * Gets username
   * @param userid
   * @return username
   */
   
   function getUserName($userID = null)
   {
      $info['table']  = USER_TBL;
      $info['fields'] = array('first_name', 'last_name', 'username');
      $info['where']  = "uid = '$userID'";
      $info['debug']  = false;

      if ($result = select($info))
      {
         foreach($result as $key => $value)
         {
            $userName = $value->first_name . ' ' . $value->last_name . ' (' . $value->username . ')';
         }
      }

      return $userName;
   }

  /**
   * This function calculate difference between two dates
   *
   * @param int -- $start (the start date)
   * @param int -- $end (the end date)
   * @return returns the difference between two date
   */
   function calcDayDiff($start, $end)
   {
      $starttime = gmmktime (0, 0, 0, substr ($start, 5, 2), substr ($start, 8, 2), substr ($start, 0, 4));
      $endtime   = gmmktime (0, 0, 0, substr ($end, 5, 2), substr ($end, 8, 2), substr ($end, 0, 4));
      $days      = (($endtime - $starttime) / 86400);
      $result    = floor ($days);

      return $result;
   }

   /**
   *
   * This function resizes the image file
   *
   * @return returns true if successful else false
   */
   function resampimage($forcedwidth = 150, $forcedheight = 150, $sourcefile, $destfile)
   {
      $g_srcfile=$sourcefile;
      $g_dstfile=$destfile;
      $g_fw=$forcedwidth;
      $g_fh=$forcedheight;

      $stats = getimagesize($sourcefile);
      if($stats === FALSE)
         return false;

      $mime = $stats['mime'];

      if (!preg_match("/jpeg/i", $mime) && !preg_match("/png/i", $mime) && !preg_match("/gif/i", $mime))
          return false;


      if(file_exists($g_srcfile))
      {
          $g_is=getimagesize($g_srcfile);
          if(($g_is[0]-$g_fw)>=($g_is[1]-$g_fh))
          {
              $g_iw=$g_fw;
              $g_ih=($g_fw/$g_is[0])*$g_is[1];
          }
          else
          {
              $g_ih=$g_fh;
              $g_iw=($g_ih/$g_is[1])*$g_is[0];
          }

          if(preg_match("/jpeg/i", $mime))
             $img_src = imagecreatefromjpeg($g_srcfile);
          else if(preg_match("/gif/i", $mime))
             $img_src = imagecreatefromgif($g_srcfile);
          else if(preg_match("/png/i", $mime))
             $img_src = imagecreatefrompng($g_srcfile);

          $img_dst=imagecreatetruecolor($g_iw,$g_ih);
          imagecopyresampled($img_dst, $img_src, 0, 0, 0, 0, $g_iw, $g_ih, $g_is[0], $g_is[1]);

          if (preg_match("/jpeg/i", $mime))
             imagejpeg($img_dst, $g_dstfile);
          else if(preg_match("/gif/i", $mime))
             imagegif($img_dst, $g_dstfile);
          else
             imagepng($img_dst, $g_dstfile);

          imagedestroy($img_dst);
          return true;
      }
      else
      {
         return false;
      }
   }

  /**
   * Purpose: export to pdf file from smarty parsed template
   *          when the subHeader1,2 and file name is given
   *
   * @param string $subHeader1-- contains the header information
   * @param string $subHeader2-- contains the header information
   * @param string $name      -- holds the pdf file name with .pdf extension
   * @return fasle
   */
   function exportToPDF($screen, $subHeader1, $subHeader2, $name, $oreintation, $fontSize = 8)
   {

      //create an instance of a PDF converter object
      $pdf=new HTML2FPDF('l','mm','letter');
      $pdf=new HTML2FPDF($oreintation,'mm','a4', $fontSize);
      //add an empty pdf page to the converter object
      $pdf->AddPage();

      //write the parsed template to the blank pdf page
      $pdf->WriteHTML($screen, $subHeader1, $subHeader2);
      //$pdf->WriteHTML($screen);

      // construct the resulting pdf file path
      $file         = PDF_DIR .'/'. $name.'.pdf';
      //echo "$file";
      $pdf->Output($file);
      
      header('Content-Type: text/plain; charset=utf-8');
      header ('Location: /pdf/'.$name.'.pdf');

   }
   
   function insertInToNoticeBoard($data)
   {
   	  $info['table'] = NOTICE_BOARD_TBL;
   	  //$info['debug'] = true;
   	  $info['data']  = $data;
   	  
   	  $result = insert($info);
   	  
   }
   
   function selectFromNoticeBoard($data)
   {
   	  $info['table'] = NOTICE_BOARD_TBL;
   	  //$info['debug'] = true;
   	  //$info['data']  = $data;
   	  $info['where'] = " 1 group by notice_section  order by notice_date DESC";
   	  
   	  $result = select($info);
   	  
   }
   
   
   function getMonthList()
   {
      for($m = 1;$m <= 12; $m++)
      {
         $month           =  date("F", mktime(0, 0, 0, $m));
         $monthArray[$m]  = $month;
      }   
      
      return $monthArray;
   }
   
   function getYearList($beforeY = 5, $afterY = 0)
   {
      for($i = date('Y')-$beforeY; $i <= date('Y')+$afterY; $i++)
      {
         $yearArray[$i] = $i;	
      }
      
      return $yearArray;
   }
   
    function getCountryList()
    {
        $info['table'] = COUNTRY_LOOKUP_TBL;
        $info['debug'] = false;
        $info['where'] = '1 ORDER BY name ASC';

        $result = select($info);       
        
        if ($result)
        {
            foreach($result as $key => $value)
            {
                $retData[$value->id] = $value->name;
            }
        }
        
        return $retData;
    }
    
    function getFileLocation($file_id = 0,$uid)
    {
        if ($file_id == 0)
        {
            return ;
        }
        
        //$uid = getFromSession('uid'); 
        
        $thisDoc  = new DocumentEntity($file_id);
        $fileName = $thisDoc->getRemoteFileName();

        $fileLocation = REL_DOCUMENT_DIR.'/'.$uid.'/'.$fileName;
        
        return $fileLocation;
    }
?>
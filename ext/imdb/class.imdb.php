<?php
/**
 * @author Fabian Beiner <fabianDOTbeinerATgmailDOTcom>
 * @version 2.0alpha (without CURL)
 *
 * @comment Original idea by David Walsh <davidATdavidwalshDOTname>, thanks! Your blog rocks ;)
 *          I did this script in the middle of the night while being ill, no guarantee for anything!
 *
 * @license http://creativecommons.org/licenses/by-sa/3.0/de/deed.en_US
 *          Creative Commons Attribution-Share Alike 3.0 Germany
 */
 
class IMDB 
{
   function __construct($url) 
   {
      $imdb_content       = $this->imdbHandler($url);
      
      str_replace(array("\r\n", "\n", "\r"), "", $imdb_content);
      
      $this->movie            = trim($this->getMatch('|<title>(.*) - IMDb</title>|Uis', $imdb_content));
      //$this->director         = trim($this->getMatch('|<h4 class="inline">Director:</h4><a (.*)>(.*)</a>|Uis', str_replace(array("\r\n", "\n", "\r"), "", $imdb_content), 2));
      $this->director         = strip_tags( trim($this->getMatch('|<h4 class="inline">    Director([a-zA-Z]*):  </h4><a (.*)>(.*)</div>|Uis', $imdb_content, 3)) );
      $this->url_director     = trim($this->getMatch('|<h4 class="inline">Director:</h4>(.*)</div><div|Uis', $imdb_content));
      $this->release_date     = trim($this->getMatch('|<h4 class="inline">Release Date:</h4>(.*)<span|Uis', $imdb_content));
      $this->run_time         = trim($this->getMatch('|Runtime:</h4>(.*)</div>|Uis',$imdb_content));
      $this->rating           = trim($this->getMatch('|Ratings: <strong><span itemprop="ratingValue">(.*)</span>|Uis', $imdb_content));
      $this->country          = trim($this->getMatch('|<h4 class="inline">Country:</h4> <a(.*)>(.*)</a>|Uis', $imdb_content, 2));
      $this->stars[]          = trim($this->getMatch('|<h4 class="inline">Stars:</h4><a(.*)>(.*)</a>|Uis', $imdb_content, 2));
      $this->stars[]          = trim($this->getMatch('|<h4 class="inline">Stars:</h4>(.*), <a (.*)>(.*)</a>|Uis', $imdb_content, 3));
      $this->stars[]          = trim($this->getMatch('|<h4 class="inline">Stars:</h4>(.*)and <a (.*)>(.*)</a>|Uis', $imdb_content, 3));
      $this->languages        = strip_tags( trim($this->getMatch('|<h4 class="inline">Language:</h4>(.*)</div>|Uis', $imdb_content, 1)) );
      $this->also_knows_as    = trim($this->getMatch('|<h4 class="inline">Also Known As:</h4>(.*)<span class="see-more inline">|Uis', $imdb_content, 1));
      $this->description      = trim($this->getMatch('|<p itemprop="description">(.*)</p>|Uis', $imdb_content, 1));
      $this->poster           = str_replace("\"", "", trim($this->getMatch('|<td rowspan="2" id="img_primary">    <a (.*) href=(.*)>(.*)<img src=(.*) style(.*) /></a></td>|Uis', $imdb_content, 4)) );
      $this->genres           = strip_tags( trim($this->getMatch('|<h4 class="inline">Genres:</h4>(.*)</div>|Uis', $imdb_content, 1)) );
      
      //print_r("generes ::: " . $this->genres . "<br>");      
      
      //print_r("Director after::: " . strip_tags($this->director) . "<br>");                 
   }
 
   function imdbHandler($input) 
   {
      if (!$this->getMatch('|^http://(.*)$|Uis', $input)) 
      {
         $tmpUrl     = 'http://imdb.com/find?s=all&q='.str_replace(' ', '+', $input).'&x=0&y=0'; 
         //echo_br("TEMP URL :::" . $tmpUrl);
         $data       = file_get_contents($tmpUrl);
         $foundMatch = $this->getMatch('|<p style="margin:0 0 0.5em 0;"><b>Media from&nbsp;<a href="(.*)" onclick="(.*)">(.*)</a> ((.*))</b></p>|Uis', $data);
         
         //print_r("Found Match ::: $foundMatch<br>");
         
         if ($foundMatch) 
         {
            $this->url = 'http://www.imdb.com'.$foundMatch;
         } 
         else 
         {
            $this->url = '';
            return str_replace("\n", '', (string)$data);
         }
      } 
      else 
      {
         $this->url = $input;
      }
      
      $data = file_get_contents($this->url); 
      
      return str_replace("\n", '', (string)$data);
   }
 
   function getMatch($regex, $content, $index = 1) 
   {
      //print_r($content);
      
      preg_match($regex, $content, $matches);
      
      return $matches[(int)$index];
   }
 
   function showOutput() 
   {
      $content .= 'Film ::: '           . $this->movie . '</p>';
      $content .= 'Director ::: '       . $this->director.'</a></p>';
      $content .= 'Stars ::: '          . implode(", ", $this->stars);
      $content .= 'Release Date ::: '   . $this->release_date.'</p>';
      $content .= 'Run Time ::: '       . $this->run_time.'</p>';
      $content .= 'Rating  ::: '        . $this->rating.'/10</p>';
      $content .= 'Country ::: '        . $this->country . '</p>';
      $content .= 'Languages ::: '      . $this->languages . '</p>';
      $content .= 'Also known as ::: '  . $this->also_knows_as . '</p>';
      $content .= 'Description ::: '    . $this->description . '</p>'; 
      $content .= 'Geners ::: '         . $this->genres . '</p>';  
      
          
      $remoteImage = $this->poster;
      $imginfo = getimagesize($remoteImage);
      header("Content-type: $imginfo[mime]");
      readfile($remoteImage);

       echo $content;
      
      echo '<img src="'. $this->poster.'">';
      
      
   }
   
   function LoadJpeg($imgname)
{
    /* Attempt to open */
    $im = @imagecreatefromjpeg($imgname);

    /* See if it failed */
    if(!$im)
    {
        /* Create a black image */
        $im  = imagecreatetruecolor(150, 30);
        $bgc = imagecolorallocate($im, 255, 255, 255);
        $tc  = imagecolorallocate($im, 0, 0, 0);

        imagefilledrectangle($im, 0, 0, 150, 30, $bgc);

        /* Output an error message */
        imagestring($im, 1, 5, 5, 'Error loading ' . $imgname, $tc);
    }

    return $im;
}


   
   function get_data($url)
   {
      $ch = curl_init();
      $timeout = 5;
      
      curl_setopt($ch,CURLOPT_URL,$url);
      curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
      curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
      
      $data = curl_exec($ch);
      
      curl_close($ch);
      
      return $data;
   }
}  
   
   
   
   
   
   
   
   
   
   
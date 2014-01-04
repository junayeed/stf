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
 
class XMLParser
{
   function __construct($url) 
   {
      $imdb_content       = $this->xmlHandler($url);
      
      //echo(str_replace(array("\r\n", "\n", "\r"), "", $imdb_content));
      
      $this->movie        = trim($this->getMatch('|<title>(.*) - IMDb</title>|Uis', $imdb_content));
      $this->director     = trim($this->getMatch('|<div class="txt-block">  <h4 class="inline">    Director:  </h4><a  href=(.*)">(.*)</a></div>|Uis', str_replace(array("\r\n", "\n", "\r"), "", $imdb_content), 2));
      $this->url_director = trim($this->getMatch('|<h4 class="inline">Director:</h4>(.*)</div><div|Uis', $imdb_content));
      $this->release_date = trim($this->getMatch('|<h4 class="inline">Release Date:</h4>(.*)<span|Uis', $imdb_content));
      $this->run_time     = trim($this->getMatch('|Runtime:</h4>(.*)</div>|Uis',$imdb_content));
      $this->rating       = trim($this->getMatch('|Ratings: <strong><span itemprop="ratingValue">(.*)</span>|Uis', $imdb_content));
      $this->country      = trim($this->getMatch('|<a href="/country/(.*) >(.*)</a>|Uis', $imdb_content, 2));
      $this->stars[]      = trim($this->getMatch('|<h4 class="inline">Stars:</h4><a(.*)>(.*)</a>|Uis', $imdb_content, 2));
      $this->stars[]      = trim($this->getMatch('|<h4 class="inline">Stars:</h4>(.*), <a (.*)>(.*)</a>|Uis', $imdb_content, 3));
      $this->stars[]      = trim($this->getMatch('|<h4 class="inline">Stars:</h4>(.*)and <a (.*)>(.*)</a>|Uis', $imdb_content, 3));
      
      print_r($this->stars);
   }
 
   function xmlHandler($input) 
   {
      $tmpUrl     = 'meeting_minutes.xml';   	
      $data       = file_get_contents($tmpUrl);
         
      return str_replace("\n",'',(string)$data);
   }
 
   function getMatch($regex, $content, $index=1) 
   {
      preg_match($regex, $content, $matches);
      
      return $matches[(int)$index];
   }
 
   function showOutput() 
   {
      $content.= 'Film ::: '         . $this->movie . '</p>';
      $content.= 'Director ::: '     . $this->director.'</a></p>';
      $content.= 'Release Date ::: ' . $this->release_date.'</p>';
      $content.= 'Run Time ::: '     . $this->run_time.'</p>';
      $content.= 'Rating  ::: '      . $this->rating.'/10</p>';
      $content.= 'Country ::: '      . $this->country . '</p>';
      
      echo $content;
      
   }
}
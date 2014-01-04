<?php

   session_start();
   $_SESSION['nav_item'] = 'home';

   header('Location: app/standard/user_home/user_home.php');

?>
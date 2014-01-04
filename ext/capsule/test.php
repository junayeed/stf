<?php
require_once 'Services/Capsule.php';
//echo phpinfo();
//die;
try {
    echo "Start";
    $capsule = new Services_Capsule('discovermv', 'ffc5faad63e41301bb68a90ac7c07116');
     //$parties = $capsule->party->getList();
    $res = $capsule->party->get(23724140);
    echo "<pre>";
     print_r($res);

     //echo "End";
} catch (Services_Capsule_Exception $e) {
    echo "<pre>";
    print_r($e);
}

?>
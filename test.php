<?php
require_once 'ipq.php';

IPQ::load("ipadr.dat");

if(is_array($_GET)&&count($_GET)>0){
    if(isset($_GET["ip"])){
        echo IPQ::find($_GET["ip"]);        
    }
}
?>
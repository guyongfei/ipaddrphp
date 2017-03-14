<?php
require_once 'ipq.php';

IPQ::load("ipadr.dat");
echo IPQ::find("200.2.3.4");
?>
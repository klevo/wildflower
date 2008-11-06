<?php

define('BASE', dirname(__FILE__) );

//requirements
require_once 'Log.php'; //PEAR Log
require BASE . '/config/database.inc.php';
require BASE . '/lib/classes/class.FrameworkRunner.php';

$main = new FrameworkRunner($db, $argv);
$main->execute();

?>
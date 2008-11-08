<?php

define('BASE', dirname(__FILE__) );

//requirements
require BASE . '/lib/classes/util/class.Logger.php';
require BASE . '/config/database.inc.php';
require BASE . '/lib/classes/class.FrameworkRunner.php';

$main = new FrameworkRunner($db, $argv);
$main->execute();

?>
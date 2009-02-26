<?php 
App::import('Security');

class WildflowerShell extends Shell {

    function hash() {
        $value = Configure::read('Security.salt') . trim($this->args[0]);
        $this->out(Security::hash($value, null, true));
    }
    
    /**
     * Launch Ruckusing database migrations shell
     *
     * Since it's launched in the CakePHP shell you can use all the 
     * CakePHP magic inside the migrations. Super sweet.
     *
     * @return void
     */
    function ruckusing() {
        define('RUCKUSING_BASE', dirname(__FILE__) . DS . '..' . DS . 'ruckusing');
        require_once RUCKUSING_BASE . '/lib/classes/util/class.Ruckusing_Logger.php';
        require_once RUCKUSING_BASE . '/config/database.inc.php';
        require_once RUCKUSING_BASE . '/lib/classes/class.Ruckusing_FrameworkRunner.php';
        $argv = $this->args;
        array_unshift($argv, "vendors/ruckusing/main.php");
        $main = new Ruckusing_FrameworkRunner($ruckusing_db_config, $argv);
        $main->execute();
    }
    
}


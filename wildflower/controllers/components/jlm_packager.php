<?php
class JlmPackagerComponent {
	
	public $wfJlmDir;
	public $appJlmDir;
    /** Files from these directories make it to the generated file */
	private $_mvcDirs = array(
	   'vendors',
	   'controllers/components',
	   'controllers'  
	);
	private $_initializedViews = array();
	
	function startup() {
		$this->wfJlmDir = WILDFLOWER_DIR . 'jlm';
		if (!is_dir($this->wfJlmDir)) trigger_error(WILDFLOWER_DIR . ' does not exist!');
	}
	
	function l18n($string) {
		function translate($pregArray) {
			return __($pregArray[1], true);
		}
		return preg_replace_callback("#<l18n>(.+?)</l18n>#is", 'translate', $string);
	}
    
    // @depracated Output is handled by Cake action
    function output() {
        header('Content-type: application/javascript');
    	echo $this->concate();
    }
    
    /**
     * Send headers to browser to cache the current request
     *
     * @param string $file Path to a file
     */
    function browserCacheHeaders($modifiedTime, $type = 'application/javascript') {
		header("Date: " . date("D, j M Y G:i:s ", $modifiedTime) . 'GMT');
		header('Content-type: ' . $type);
		header("Expires: " . gmdate("D, j M Y H:i:s", time() + DAY) . " GMT");
		header("Cache-Control: cache");
		header("Pragma: cache");
    }
    
    /**
     * Concate all files into one string
     *
     * @return string
     */
    function concate() {
        $output = '';
        
        // Append lib files in this order
        $libDir = $this->wfJlmDir . DS . 'lib' . DS;
        $output .= $this->readFile($libDir . 'jquery.js');
        $output .= $this->readFile($libDir . 'functions.js');
        $output .= $this->readFile($libDir . 'trimpath-template.js');
        $output .= $this->readFile($libDir . 'jquery.jlm.js');
        
        // First load Wildflower templates
        $viewsPath = $this->wfJlmDir . DS . 'views';
        $output .= $this->readTemplates($viewsPath);
        
        // Load other MVC dirs
        foreach ($this->_mvcDirs as $dir) {
            $wfDirPath = $this->wfJlmDir . DS . $dir;
            $output .= $this->readMvcFiles($wfDirPath);
        }

		// Translate
		$output = $this->l18n($output);
        
        return $output;
    }
    
    /**
     * Returns string of .js concated files
     *
     * @param string $dirPath
     * @return string
     */
    function readMvcFiles($dirPath) {
        if (!is_dir($dirPath)) {
            return '';
        }
        
        $output = '';
        $files = scandir($dirPath);
        foreach ($files as $file) {
            $ext = substr($file, -3);
            if ($ext !== '.js') {
                continue;
            }
            
            $path = $dirPath . DS . $file;
            $output .= $this->readFile($path);
        }
        return $output;
    }
    
    /**
     * Returns string of templates
     *
     * @param string $viewsPath
     * @return string
     */
    function readTemplates($viewsPath) {
        if (!is_dir($viewsPath)) {
            return '';
        }
        
        $viewDirs = scandir($viewsPath);
        $output = '';
        foreach ($viewDirs as $dir) {
            if ($dir[0] == '.') {
                continue;
            }
            
            // Init specific template array
            if (!in_array($dir, $this->_initializedViews)) {
                $output .= "jQuery.jlm.templates['$dir'] = [];\n";
                $this->_initializedViews[] = $dir;
            }
            
            $viewDirPath = $viewsPath . DS . $dir;
            if (!is_dir($viewDirPath)) {
                continue;
            }
            
            $files = scandir($viewDirPath);
            foreach ($files as $file) {
                $ext = substr($file, -5);
                if ($ext !== '.html') {
                    continue;
                }
                
                $path = $viewDirPath . DS . $file;
                $template = $this->readFile($path, false);
                
                // Escape some chars
                $template = str_replace(array("\n", "\r", "'"), array('', '', "\'"), $template);
                $templateName = str_replace('.html', '', $file);
                $varName = "jQuery.jlm.templates['$dir']['$templateName']";
                
                $output .= "$varName = '$template';\n";
            }
        }
        return $output;
    }
    
    function readFile($path) {
    	$output = "\n";
        $output .= file_get_contents($path);
        return $output;
    }
	
}

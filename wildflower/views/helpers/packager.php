<?php
App::import('Vendor', 'jsmin');

/**
 * Packager Helper
 * 
 * Create groups of Cascading Style Sheet and/or JavaScript files to link, join & compress.
 */
class PackagerHelper extends AppHelper {
	
	public $helpers = array('Html', 'Javascript');
	private $_cssDir;
	private $_jsDir;
	private $_rootDir;
	
	function __construct() {
		parent::__construct();
		$this->_cssDir = WWW_ROOT . 'css' . DS;
		$this->_jsDir = WWW_ROOT . 'js' . DS;
		$this->_rootDir = WWW_ROOT;
	}
    
	/**
	 * Gzip some file and put it to webroot
	 *
	 * @param string $filename
	 * @param string $data
	 */
	function gzip($filename, $data) {
		$gz = gzopen($this->_rootDir . $filename . '.gz', 'w9');
		gzwrite($gz, $data);
		gzclose($gz);
	}
	
	/**
	 * Include Javascript files
	 *
	 * @param array $files
	 * @return string HTML JavaScript file include
	 */
    function js($files = array()) {
    	// Output links to normal uncompressed files if developing
        if (Configure::read('debug') > 0) {
            return $this->Javascript->link($files);
        }
        
    	// Create new packed files if not up to date
    	$jsBaseName = 'cache.js';
    	$jsFilePath = "{$this->_jsDir}$jsBaseName";
    	
    	// Create the cache if it doesn't exist
    	if (!file_exists($jsFilePath)) {
    		$save = '';
    		
	    	// Get content of all files
	        foreach ($files as &$file) {
	            $file = str_replace('/', DS, $file); // replace URL dash with file system dash
	            $ext = substr(strrchr($file, '.'), 1);
	            
	            // Add .js file extension if using short file name
	            if ($ext !== '.js') {
	                $file = "$file.js";
	            }
	            $path = "{$this->_jsDir}$file";
	            
	            // Add file content
	            $save .= file_get_contents($path);
	        }
	        
	        // Minify the JavaScript content
	        $save = JSMin::minify($save);
	         
	        $file = fopen($jsFilePath, 'w');
	        fwrite($file, $save);
	        fclose($file);
    	}

		return $this->Javascript->link($jsBaseName);
    }
	
	/**
	 * Include Javascript files
	 *
	 * @param array $files
	 * @return string HTML JavaScript file include
	 */
    function css($files = array()) {
    	// Create new packed files if not up to date
    	$cacheFileName = 'cache.css';
    	$cacheFilePath = "{$this->_cssDir}$cacheFileName";
    	$cacheModified = filemtime($cacheFilePath);
    	
    	$newestCssFileModified = 0;
    	foreach ($files as $file) {
    		if ($ext !== '.css') {
    			$file = "$file.css";
    		}
    		$path = "{$this->_cssDir}$file";
    		$modified = filemtime($path);
    		if ($modified > $newestCssFileModified) {
    			$newestCssFileModified = $modified;
    		}
    	}
    	
    	// Create the cache if it doesn't exist
    	if ($newestCssFileModified > $cacheModified) {
    		echo 'creating cache';
    		$save = '';
    		
	    	// Get content of all files
	        foreach ($files as &$file) {
	            $file = str_replace('/', DS, $file); // replace URL dash with file system dash
	            $ext = substr(strrchr($file, '.'), 1);
	            
	            // Add .css file extension if using short file name
	            if ($ext !== '.css') {
	                $file = "$file.css";
	            }
	            $path = "{$this->_cssDir}$file";
	            
	            // Add file content
	            $save .= file_get_contents($path);
	        }
	        
	        // Compress CSS
		    $save = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $save);
		    $save = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $save);

		    // Write the cache file
	        $file = fopen($cacheFilePath, 'w');
	        fwrite($file, $save);
	        fclose($file);
    	}

		return $this->Html->css($cacheFileName);
    }
	
}
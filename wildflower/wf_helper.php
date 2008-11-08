<?php
class WildflowerHelper extends Helper {

    /**
     * Returns class="..." piece of HTML code. Useful for generating various lists where 
     * you need to classify each item.
     *
     * @param mixed $cssClasses
     * @return string
     */
    function getClassAttr($cssClasses) {
        if (empty($cssClasses)) {
            return '';
        }
        if (is_array($cssClasses)) {
            $cssClasses = implode(' ', $cssClasses);
        }
        return ' class="' . $cssClasses . '"';
    }
    
    /**
     * Returns id="..." piece of HTML code
     *
     * @param mixed $cssIds
     * @return string
     */
    function getIdAttr($cssIds) {
        if (empty($cssIds)) {
            return '';
        }
        if (is_array($cssIds)) {
            $cssIds = implode(' ', $cssIds);
        }
        return ' id="' . $cssIds . '"';
    }
    
    /**
     * Checks if the currently render page matches the $slug
     *
     * @deprecated: Use ($link == $this->here) to check for such things. URI is the best
     * identifier of a RESTful web application.
     * 
     * @param string $slug
     * @return bool
     */
    function isCurrentPage($slug) {
        if (isset($this->params['current']['slug']) and $this->params['current']['slug'] == $slug) {
            return true;
        }
        return false;
    }
    
	/**
	 * Returns a string with all spaces converted to $replacement and non word characters removed.
	 *
	 * @param string $string
	 * @param string $replacement
	 * @return string
	 * @static
	 */
    static function slug($string, $replacement = '-') {
    	$string = trim($string);
        $map = array(
            '/à|á|å|â|ä/' => 'a',
            '/è|é|ê|ẽ|ë/' => 'e',
            '/ì|í|î/' => 'i',
            '/ò|ó|ô|ø/' => 'o',
            '/ù|ú|ů|û/' => 'u',
            '/ç|č/' => 'c',
            '/ñ|ň/' => 'n',
            '/ľ/' => 'l',
            '/ý/' => 'y',
            '/ť/' => 't',
            '/ž/' => 'z',
            '/š/' => 's',
            '/æ/' => 'ae',
            '/ö/' => 'oe',
            '/ü/' => 'ue',
            '/Ä/' => 'Ae',
            '/Ü/' => 'Ue',
            '/Ö/' => 'Oe',
            '/ß/' => 'ss',
            '/[^\w\s]/' => ' ',
            '/\\s+/' => $replacement,
            String::insert('/^[:replacement]+|[:replacement]+$/', 
            array('replacement' => preg_quote($replacement, '/'))) => '',
        );
        $string = preg_replace(array_keys($map), array_values($map), $string);
        return low($string);
    }
	
} 

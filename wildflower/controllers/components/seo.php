<?php
/**
 * SEO Component
 * 
 */
class SeoComponent extends Object {
	
	/**
	 * Current controller
	 *
	 * @var AppController
	 */
	private $controller;
	
	function startup($controller) {
		$this->controller = $controller;
	}
	
	/**
	 * Build SEO title
	 *
	 * @param string $pageTitle Title of the current item/page/posts...
	 */
    function title($pageTitle = null) {
        if (!is_object($this->controller)) {
            return;
        }
        
    	if (!$pageTitle) {
    	   $pageTitle = $this->controller->pageTitle;
    	}
    	if (!$pageTitle) {
    	   $pageTitle = ucwords($this->controller->params['controller']);
    	}
    	
    	$description = Configure::read('AppSettings.description');
    	$nameAndDescription = hsc(Configure::read('AppSettings.site_name'));
    	if ($description) {
    	    $description = hsc($description);
    	    $nameAndDescription = "$nameAndDescription - {$description}";
    	}
    	
        if ($this->controller->isHome) {
            $this->controller->pageTitle = $nameAndDescription;
        } else {
            $this->controller->pageTitle = "$pageTitle &#8226; $nameAndDescription";
        }
        
        $this->controller->set('page_title_for_layout', $pageTitle);
        $this->controller->set('site_title_for_layout', $nameAndDescription);
    }
	
}

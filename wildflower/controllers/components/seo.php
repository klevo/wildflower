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
    	
    	$description = hsc(Configure::read('Wildflower.settings.description'));
	$keywords = hsc(Configure::read('Wildflower.settings.keywords'));
    	$credits = Configure::read('Wildflower.settings.credits');
    	$homepage_credits = Configure::read('Wildflower.settings.homepage_credits');
    	$nameAndDescription = hsc(Configure::read('Wildflower.settings.site_name'));
    	if ($description) {
    	    $nameAndDescription = "$nameAndDescription - {$description}";
    	}
    	
        if ($this->controller->isHome) {
            $this->controller->pageTitle = $nameAndDescription;
        } else {
            $this->controller->pageTitle = "$pageTitle &#8226; $nameAndDescription";
		    // Uncomment below for homepage only credits
		    if($homepage_credits){
		    	$credits = "";
		    }
        }
        
        $this->controller->set('page_title_for_layout', $pageTitle);
        $this->controller->set('title_for_layout', $nameAndDescription);
		$this->controller->set('descriptionMetaTag', $description);
        $this->controller->set('keywordsMetaTag', $keywords);
        $this->controller->set('credits', $credits);
    }
	
}

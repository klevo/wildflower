<?php
App::import('Vendor', 'SimpleHtmlDom', array('file' => 'simple_html_dom.php'));

class WildHelper extends AppHelper {
	
	public $helpers = array('Html', 'Textile', 'Htmla', 'Form');
	private $_isFirstChild = true;
	private $itemCssClassPrefix;
	
	/**
	 * Get home (/) link tag with site name as label
	 *
	 * @param string $siteName
	 * @return string Link HTML
	 */
	function homeLink($siteName) {
	    $siteName = hsc($siteName);
	    return $this->Html->link("<span>$siteName</span>", '/', array('title' => 'Home'), null, null, false);
	}
	
	function keyWordsMetaTag($default = '') {
	    if (isset($this->params['pageMeta']['keywordsMetaTag']) && !empty($this->params['pageMeta']['keywordsMetaTag'])) {
	        return hsc($this->params['pageMeta']['keywordsMetaTag']);
	    }
	    
	    return $default;
	}
	
	/**
	 * returns a link to favicon to be echoed in head of layout
	 *
	 * @return string
	 */
	function icon($icon = 'favicon.ico') {
		return $this->Html->tag('link', null, array('rel' => 'shortcut icon', 'href' => $this->webroot . $icon, 'type' => 'image/x-icon'));
	}
	
	/**
	 * Generate <body> tag with class attribute. Values are
	 * home-page, page-view, post-view.
	 *
	 * @return string
	 */
	function rss($options = array()) {

		$head = true; $url = '/rss'; $display = $title = 'rss'; $options = array();

		if($options > array())	{
			extract($options);
		}

		$url = $this->Html->url($url);

		if($head)	{
			$options = array(
					'rel' => 'alternate', 
					'href' => $url, 
					'type' => 'application/rss+xml'
				);
			return $this->Html->tag('link', null, $options);
		} else {
			if(isset($options['display']))	unset($options['display']);
			if(isset($options['url']))	unset($options['url']);
			return $this->Html->link($display, $url, $options);
		}
	}

	function title($title) {
		if(Configure::read('debug'))	{
			$title.= ' Cake: ' . Configure::read('Cake.version');
			$title.= ' WF: ' . Configure::read('Wildflower.version');
			$title.= ' Theme: ' . 'later';
		}
		return $this->Html->tag('title', $title);
	}
	
	/**
	 * display search form
	 *
	 * $mData is array of meta, blank empty array use preset
	 *
	 * @return string
	 */
	function search() {
		$return = $this->Form->create("Dashboard", array('url' => '/search', 'type' => 'get'));
	    $return.= $this->Form->input("q", array('label' => false));
	    $return.= $this->Form->end('Search');
		return $return;
	}
	
	/**
	 * Generate metatags
	 *
	 * $mData is array of meta, blank empty array use preset
	 *
	 * @return string
	 */
	function meta($mData = array()) {
		$return = '';
		$meta = array('description' => true, 'keywords' => true);

		$mData = array_merge($meta, $mData);

		extract($mData);
		$meta = array();
		$meta['keywords'] = (isset($keywords) && $keywords !== true) ? $keywords : Configure::read('Wildflower.settings.keywords');
		$meta['description'] = (isset($description) && $description !== true) ? $description : Configure::read('Wildflower.settings.description');

		foreach($meta as $name => $content)	{
			if($content)	{
				$return.= $this->Html->meta($name, $content);
			}
		}
		return $return;
	}
	
	/**
	 * Generate <body> tag with class attribute. Values are
	 * home-page, page-view, post-view.
	 *
	 * @return string
	 */
	function bodyTagWithClass() {
		if (!isset($this->params['Wildflower']['view'])) {
			return '<body>';
		}
		
	    extract($this->params['Wildflower']['view']);
	    $html = '<body';
	    if ($isHome) { 
	       $html .= ' class="home-page"'; 
	    } else if ($isPage) {
	       $pageSlug = '';
	       if (isset($this->params['current']['slug'])) {
              $pageSlug = ' ' . $this->params['current']['slug'] . '-page';           
	       }
	       $html .= ' class="page-view' . $pageSlug . '"'; 
	    } else if ($isPosts) { 
	       $html .= ' class="post-view"'; 
	    } else if (isset($this->params['current']['body_class'])) {
	       $html .= " class=\"{$this->params['current']['body_class']}\"";
	    }
	    $html .= '>';
        return $html;
	}
	
	function descriptionMetaTag($default = '') {
        if (isset($this->params['pageMeta']['descriptionMetaTag']) && !empty($this->params['pageMeta']['descriptionMetaTag'])) {
            return hsc($this->params['pageMeta']['descriptionMetaTag']);
        }
        
        return $default;
    }
    
    function menu($slug, $options = null) {
    	$items = $this->getMenuItems($slug);
    	if (empty($items)) {
    	    return '<p>' . __('Wildflower: There are no menu items for this menu.', true) . '</p>';
    	}
    	$links = array();
		$view = ClassRegistry::getObject('view');
    	foreach ($items as $item) {
    	    $label = hsc($item['label']);
    	    $slug = self::slug($item['label']);
    	    $classes = array('nav-' . $slug);
    	    $isCurrent = (rtrim($this->here, '/') . '/' === rtrim($this->Html->url($item['url']), '/') . '/');
			
	        if (isset($view->viewVars['current_link_for_layout']) && $view->viewVars['current_link_for_layout'] === $item['url']) {
				$isCurrent = true;
			}

    	    if ($isCurrent) {
    	        $classes[] = 'current';
    	    }
    	    $links[] = '<li class="' . join(' ', $classes) . '">' . $this->Html->link("<span>$label</span>", $item['url'], array('escape' => false)) . '</li>';
    	}
    	$links = join("\n", $links);
		$id = "$slug";
		if (isset($options['id'])) {
			$id = $options['id'];
		}
    	return "<ul id=\"$id\">\n$links\n</ul>\n";
    }
    
    function getMenuItems($slug) {
        $Menu = ClassRegistry::init('Menu');
        $Menu->contain(array('MenuItem' => array('order' => 'MenuItem.order ASC')));
        $menu = $Menu->findBySlug($slug);
        return $menu['MenuItem'];
    }
    
    function submit($label = 'Save') {
        $button = '<div class="submit"><button type="submit"><span class="bl1"><span class="bl2">' . $label . '</span></span></button></div>';
        return $button;
    }
    
    private function _createListNode($label, $link, $childPages = null) {
        $slug = $this->_getMenuSlug($label);
        $label = hsc($label);
        $node = $this->Html->link("<span>$label</span>", $link, array('class' => "{$this->itemCssClassPrefix}$slug-link"), null, false);
    
        if ($childPages) {
            $node .= " {$this->menu($childPages)}";
        }
        
        $liAttr = array();
        if ($this->_isFirstChild) {
            $liAttr[] = 'first';
            $this->_isFirstChild = false;
        }
        if ($link == $this->here) {
        	$liAttr[] = 'current';
        }
        
        $liAttr = $this->getClassAttr($liAttr);
        return "<li$liAttr>$node</li>\n";
    }
    
    function processWidgets($html) {
        // Find the widget element
        $selector = '.admin_widget';
        $dom = str_get_html($html);
        $widgets = $dom->find($selector);
        $Widget = ClassRegistry::init('Widget');
        $view = ClassRegistry::getObject('view');
        foreach ($widgets as $widget) {
            $widgetId = $widget->id;
            $widgetClass = $widget->class;
            $instanceId = intval(r('admin_widget admin_widget_id_', '', $widgetClass));
            $data = $Widget->findById($instanceId);
            $data = json_decode($data['Widget']['config'], true);
            $replaceWith = $view->element('widgets/' . $widgetId, array('data' => $data));
            $replace = $widget->outertext;
            if ($widget->parent()->tag == 'p') {
                $replace = $widget->parent()->outertext;
            }
            // Replace the widget placeholder with real stuff
            $html = r($replace, $replaceWith, $html);
        }
        return $html;
    }
    
    function subPageNav() {
        $html = '<ul>';
        $pageSlug = end(array_filter(explode('/', $this->params['url']['url'])));
        $Page = ClassRegistry::init('Page');
        $Page->recursive = -1;
        
        // Get the parent page slug
        $url = $this->params['url']['url'];
        $slug = array_shift(explode('/', $url));
        $pages = $Page->findAllBySlugWithChildren($slug);

        if (empty($pages)) {
            return '';
        }
        
        // Build HTML
        foreach ($pages as $page) {
            $html .= '<li>' . $this->Htmla->link($page['Page']['title'], $page['Page']['url'], array('strict' => true)) . '</li>';
        }
        $html .= '</ul>';
        return $html;
    }
    
    function postsFromCategory($slug) {
        $Category = ClassRegistry::init('Category');
        $Category->contain(array(
            'Post' => array(
                'conditions' => array(
                    'draft' => 0
                )
            ),
            'Post.User'
        ));
        $category = $Category->findBySlug($slug);
        $posts = $category['Post'];
        return $posts;
    }

	function dateWithTime($time) {
        if (!is_integer($time)) {
            $time = strtotime($time);
        }
        return date('M j, Y, g:ia', $time);
    }
        
    function date($time) {
        if (!is_integer($time)) {
            $time = strtotime($time);
        }
        return date('M j, Y', $time);
    }    
    
    function epicTime($time) {
        if (!is_integer($time)) {
            $time = strtotime($time);
        }
        return date('l', $time) . ', ' . date('F j, Y', $time);
    }

}
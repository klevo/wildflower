<?php
/**
 * Navigation Helper
 * 
 */
class NavigationHelper extends AppHelper {
    
    public $helpers = array('Html');
    private $_defaultOptions = array(
        'id' => '',
        'itemTemplate' => '<li%attr%><a href="%url%"><span>%name%</span></a></li>',
        'activeItemTemplate' => '<li%attr%><a href="%url%"><span>%name%</span></a></li>',
        'activeCssClass' => 'current',
        'before' => '<ul%attr%>',
        'after' => '</ul>'
    );
    private $_treeSettings = array(
        'model' => 'Page',
        'alias' => 'title',
        'left' => 'lft',
        'right' => 'rght',
        'primaryKey' => 'id'
    );
    private $_limits = null;
    private $_childCount = false;
    private $_toOutput = '';
    
    /**
     * Save Google sitemap @TODO
     *
     * @param unknown_type $structure
     */
    function saveGoogleSitemap($structure = array()) {
    	$xmlTemplate =   '<?xml version="1.0" encoding="UTF-8"?>
						  <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
						   %urlset%  
						  </urlset>';
    	$urlTemplate =    '<url>
		                    <loc>%loc%</loc>
		                    <lastmod>%lastmod%</lastmod>
		                    <changefreq>%changefreq%</changefreq>
		                    <priority>%priority%</priority>
		                   </url>';
    	
    	$data = '';
    	
    	$this->Packager->gzip('sitemap.xml');
    }
    
    /**
     * Create navigation
     * Generate an unordered list of links. Adds "current" CSS class to current item.
     * 
     * @param array $items
     * @param array $settings Options array
     * @return string List HTML
     */
    function create($items = array(), $settings = array(), $toOutput = false) {
        $settings = am($this->_defaultOptions, $settings);
        
        // Begin list
        $ulAttr = '';
        if (!empty($settings['id'])) {
        	$ulAttr .= " id=\"{$settings['id']}\"";
        }
        if (!empty($settings['class'])) {
        	$ulAttr .= " class=\"{$settings['class']}\"";
        }
        $html = str_replace('%attr%', $ulAttr, $settings['before']);
        
        // List nodes
        foreach ($items as $name => $url) {
            $isCurrent = false;
            
            if (is_int($name)) {
                $name = $url;
                $url = '/' . low($url);
                $url = $this->Html->url($url);
            } else if (is_string($url)) {
                $url = $this->Html->url($url);
                if ($this->params['action'] === 'index') {
                    
                }
                $isCurrent = ($this->here === $url);
            } else if (is_array($url)) {
                $keys = array('controller', 'action');
                if (isset($url['controller']) && (!isset($url['action']) || $url['action'] === 'index')) {
                    // We can decide by comparing controllers
                    $isCurrent = ($url['controller'] === $this->params['controller']);
                } else if (isset($url['controller']) and isset($url['action'])) {
                    $isCurrent = ($url['controller'] === $this->params['controller']) && ($url['action'] === $this->params['action']);
                }
                
                // This is basically a hack to get a nice url in form of wf/controller-name/ instead of wf/controller-name/index
                if (!isset($url['action']) || $url['action'] === 'wf_index') {
                    $url = '/' . Configure::read('Wildflower.prefix') . '/' . str_replace('', '', $url['controller']);
                }
                
                $url = $this->Html->url($url);
            }
            
            $liAttr = '';
            
            $slug = AppHelper::slug(low($name));
            $cssClasses = array();
            if (!empty($settings['id'])) {
                $cssClasses[] = "{$settings['id']}-$slug";
            } else if (!empty($settings['class'])) {
                $firstClass = array_shift(explode(' ', $settings['class']));
                $cssClasses[] = "$firstClass-$slug";
            }
            
            $itemTemplate = $settings['itemTemplate'];
            
            if ($isCurrent) {
                $cssClasses[] = $settings['activeCssClass'];
                $itemTemplate = $settings['activeItemTemplate'];
            }
            
            $cssClasses = implode(' ', $cssClasses);
            $liAttr = " class=\"$cssClasses\"";
            
            // Translation
            $name = __($name, true);
            
            $html .= str_replace(array('%attr%', '%url%', '%name%'), array($liAttr, $url, $name), $itemTemplate);
        	$html .= "\n";
        }
        $html .= $settings['after'];
        $html .= "\n";
        
        if ($toOutput) {
            $this->_toOutput .= $toOutput;
            return null;
        }
        
        return $html;
    }
    
    /**
     * Create nice pagination
     *
     * @return string Paginator HTML
     */
    function paginator() {
    	$model = ucwords(Inflector::singularize($this->params['controller']));
    	$pageCount = $this->params['paging'][$model]['pageCount'];
        // Show paginator only if there are at least two pages
        if ($pageCount < 2) {
            return '';
        }
        
        // For index actions we need to apped index/ string before the named arg
        $index = '';
        if (in_array($this->params['action'], array('index', 'admin_index'))) {
        	$index = 'index/';
        }
    	
	    $currentPage = $this->params['paging'][$model]['page'];
	    $currentPageString = "/{$index}page:{$currentPage}";
	    
	    $currentUrl = $this->here;
	    // Remove base from the url
	    if (!empty($this->base) and strpos($currentUrl, $this->base) === 0) {
	        $currentUrl = substr($currentUrl, strlen($this->base) - strlen($currentUrl));
	    }
	    
	    // If there's no page string in the URL append one
	    if (strpos($currentUrl, $currentPageString) === false) {
	    	// Replace the trailing slash if exists
	    	$cCount = strlen($currentUrl) - 1;
	    	if ($currentUrl[$cCount] == '/') {
	    		$currentUrl[$cCount] = '';
	    	}
	    	$currentUrl .= $currentPageString; // @TODO this can cause problems with named args
	    }
	    
	    $links = array();
	    for ($i = 1; $i <= $pageCount; $i++) {
	        $class = 'paginate-page';
	        if ($i == $currentPage) {
	            $class .= ' current';
	        }
	        
	        if ($i == 1) {
	        	$newPageString = "";
                $url = str_replace($currentPageString, $newPageString, $currentUrl);
	        	$links[] = $this->Html->link("$i", $url, array('class' => $class));
	        } else {
	        	$newPageString = "/{$index}page:$i";
                $url = str_replace($currentPageString, $newPageString, $currentUrl);
		        $links[] = $this->Html->link("$i", $url, array('class' => $class));
	        }
	    }
	    $links = join(' ', $links);
	    
	    // Generate prev and next links
	    $next = $prev = '';
	    if ($this->params['paging'][$model]['nextPage']) {
	        $nextPage = $currentPage + 1;
	        $newPageString = "/{$index}page:$nextPage";
            $url = str_replace($currentPageString, $newPageString, $currentUrl);
	        $next = $this->Html->link('Next »', $url, array('class' => 'paginate-page paginate-next'));
	    } else {
	    	$next = '<span class="paginate-page paginate-next">Next »</span>';
	    }
	    
	    if ($this->params['paging'][$model]['prevPage']) {
	        $prevPage = $currentPage - 1;
	        $newPageString = "/{$index}page:$prevPage";
	        if ($prevPage == 1) {
	        	$newPageString = '';
	        }
            $url = str_replace($currentPageString, $newPageString, $currentUrl);
	        $prev = $this->Html->link('« Previous', $url, array('class' => 'paginate-page paginate-prev'));
	    } else {
            $prev = '<span class="paginate-page paginate-prev">« Previous</span>';
        }
	    
	    return "<div class=\"paginator\">$prev $links $next</div>";
    }
    
    /**
     * Create menu of all child pages of current root page
     *
     * @return string
     */
    function sectionMenu() {
    	// Get the second item from breadcrumb, first is home
    	if (!isset($this->params['breadcrumb'][1]['id'])) {
    		return;
    	}
    	$left = $this->params['breadcrumb'][1]['lft'];
    	$right = $this->params['breadcrumb'][1]['rght'];
    	
    	$pages = $this->requestAction("/pages/getBranch/$left/$right");
    	
    	// Remove the root page
    	array_shift($pages);
    	
    	if (empty($pages)) {
    		return;
    	}
    	
    	$sectionTitle = $this->params['breadcrumb'][1]['title'];
    	$return = "\n<h4 class=\"parent-name\">$sectionTitle</h4>\n";
    	$currentPageId = $this->params['current']['id'];
    	$return .= $this->generateMPTT($pages, array('currentPageId' => $currentPageId), 'subpages');
    	return $return;
    }
    
    /**
     * Create menu of all child pages of current root page
     *
     * @return string
     */
    function pages($options = array()) {
        $pages = $this->requestAction('/wildflower/pages/navigation', array(
            'navOptions' => $options
        ));
        $idAttr = '';
        if (isset($options['id'])) {
        	$id = $options['id'];
        	$idAttr = " id=\"$id\"";
        }
        
        // Build list
        $html = "<ul$idAttr>\n";
        $first = true;
        foreach ($pages as $page) {
        	$classes = array();
        	if ($first) {
        		$classes[] = 'first';
        		$first = false;
        	}
        	if (isset($this->params['current']['id'])
        	   && $this->params['current']['id'] == $page['Page']['id']) {
        		$classes[] = 'current';
        	}
        	
        	$classAttr = '';
        	if (!empty($classes)) {
        	   $_classes = implode(' ', $classes);
        	   $classAttr = " class=\"$_classes\"";
        	}
        	
        	$html .= "<li$classAttr>" . $this->Html->link($page['Page']['title'], $page['Page']['url']) . "</li>\n";
        }
        $html .= "</ul>\n\n";
        
        return $html;
    }
    
    /**
     * Generate nested list from tree data
     *
     * @param array $data
     * @param array $indexArray Settings
     * @param string $ulClass
     * @return string Nested list
     */
    function generateMPTT($data, $indexArray = array(), $ulClass = 'code treeview') {
    	extract($this->_treeSettings);
    	extract($indexArray);
    	
    	// determin limits to know when the last top node is found.
    	if (isset($data[0][$model][$left])) {
    		$floor= $data[0][$model][$left];
    		$ceil= $data[0][$model][$right];
    		foreach ($data as $node) {
    			if ($node[$model][$right] > $ceil) {
    				$ceil= $node[$model][$right];
    			}
    		}
    	} else {
    		$floor = $ceil = 0;
    	}
    	$this->_limits = array(array($floor - 1, $ceil + 1));
    	
    	$this->_childCount= 1;
    	
    	if ($ulClass) {
    		$return= "\r\n" . '<ul class="' . $ulClass . '">' . "\n";
    	} else {
    		$return= "\n" . '<ul>' . "\n";
    	}
    	
    	foreach ($data as $i => $node) {
    		$return .= "\n" . str_repeat("\t", $this->_childCount);
    		$hasChildren= false;
    		$last= false;
    		$class= array ();
    		if ($node[$model][$right] <> $node[$model][$left] + 1) { // Has some children
    			$hasChildren= true;
    			list ($parentLeft, $parentRight)= $this->_get_parent_indexes($node[$model][$left]);
    			$last= ife($parentRight == ($node[$model][$right] + 1), true, false);
    			$this->_limits[]= array (
    			$node[$model][$left],
    			$node[$model][$right]
    			);
    		}
    		if (!$hasChildren && (!isset ($data[$i +1]) || ($node[$model][$right] + 1 <> $data[$i +1][$model][$left]))) {
    			// it's the last item or the last in the current series
    			$last= true;
    			$class[]= 'last';
    		}
    		
    		// Is current?
    	    if ($node[$model]['id'] == $this->params['current']['id']) {
                $class[]= 'current';
            }
    		
    		
    		if ($class) {
    			$return .= '<li class="' . implode($class, ' ') . '">';
    		} else {
    			$return .= '<li>';
    		}
    		// Title with edit link
    		$title = $node[$model][$alias];
    		$id = $node[$model][$primaryKey];
    		$controller = low(Inflector::pluralize($model));
    		$lModel = low($model);
    		
    		// URL
    		$url = $node[$model]['url'];
    		if (Configure::read('AppSettings.home_page_id') == $node[$model]['id']) {
    		    $url = '/';
    		}
    		
    		$return .= $this->Html->link($title, $url);
    		if (isset ($data[$i +1])) {
    			// If it's not the absolute last item
    			if ($node[$model][$right] < $data[$i +1][$model][$left]) { // Close uls
    				for ($j= 1; $j <= ($data[$i +1][$model][$left] - $node[$model][$right] - 1); $j++) {
    					$this->_childCount--;
    					if ($this->_childCount < 0) {
    						trigger_error(__('child count less than 0 in ' . __METHOD__, E_USER_WARNING));
    					}
    					$return .= '</li>';
    					$return .= "\n" . str_repeat("\t", $this->_childCount) . '</ul>';
    				}
    				$return .= '</li>';
    			}
    			elseif ($node[$model][$right] <> $node[$model][$left] + 1) { // Has some children
    				$return .= "\n" . str_repeat("\t", $this->_childCount) . '<ul>';
    				$this->_childCount++;
    			} else {
    				$return .= '</li>';
    			}
    		} else {
    			// Last item in data list, close ul items
    			$return .= '</li>';
    			for (; $this->_childCount > 1; $this->_childCount--) {
    				$return .= "\n" . str_repeat("\t", $this->_childCount - 1) . '</ul>';
    				$return .= "\n" . str_repeat("\t", $this->_childCount - 1) . '</li>';
    			}
    		}
    	}
    	$return .= "\n" . '</ul>' . "\n";
    	return $return;
    }
    
    function _get_parent_indexes($thisLeft) {
        if (!$this->_limits) {
            return array (
                0,
                0
            );
        }
        $parentLeft= $this->_limits[count($this->_limits) - 1][0];
        $parentRight= $this->_limits[count($this->_limits) - 1][1];
        if ($parentRight < $thisLeft) {
            unset ($this->_limits[count($this->_limits) - 1]);
            $this->_limits= array_values($this->_limits);
            return $this->_get_parent_indexes($thisLeft);
        }
        return array (
            $parentLeft,
            $parentRight
        );
    }
    
}

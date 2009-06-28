<?php
/**
 * Category Helper
 *
 */
class BreadcrumbHelper extends AppHelper {

	public $helpers = array('Html');

	/**
	 */
	function create() {
		$_breadcrumb = array();
		$itemCount = count($this->params['breadcrumb']);
		for ($i = 0; $i < $itemCount; $i++) {
			if ($i == ($itemCount - 1)) {
				$_breadcrumb[] = $this->params['breadcrumb'][$i]['title'];
			} else {
                $_breadcrumb[] = $this->Html->link($this->params['breadcrumb'][$i]['title'], $this->params['breadcrumb'][$i]['url']);				
			}
		}

		$breadcrumb = join(' &rarr; ', $_breadcrumb);
		return $breadcrumb;    
	}

}

<?php
class ImageSizesBrowserHelper extends AppHelper {
	
	public $helpers = array('Html');
	private $_currentSize = 'm';
	
	/**
	 * Create image sizes browser
	 *
	 * @param array $image $upload['Upload']
	 * @param array $sizes Sizes definition array from controller
	 * @return string HTML
	 */
	function create($image, $sizes) {
        if (isset($this->params['named']['size'])) {
        	$this->_currentSize = trim($this->params['named']['size']);
        }
		
		$html = '<ul class="sizes-list">' . "\n";
		
		foreach ($sizes as $size => $params) {
			$html .= '<li>';
			
			if (!isset($this->params['pass'][0])) {
				trigger_error('There is no ID for this action.');
			}
			$imageId = $this->params['pass'][0];
			
			$class = '';
			if ($this->_currentSize == $params['sufix']) {
				$class = 'current';
			}
			
			$html .= $this->Html->link(
                    "{$params['label']}<br /><small>{$params['width']}x{$params['height']}</small>", 
                    array('action' => 'edit', 'size' => $params['sufix'], $imageId),
                    array('class' => $class),
                    null,
                    false);
            
           $html .= "</li>\n";
		}
		
		$html .= "</ul>\n\n";
		
		// Finaly append the image
		if ($this->_currentSize == 'o') {
			// 
			$path = "/uploads/{$this->data['Upload']['name']}";
		} else {
			$path = "/img/thumb/{$this->data['Upload']['name']}/{$sizes[$this->_currentSize]['width']}/{$sizes[$this->_currentSize]['height']}/{$sizes[$this->_currentSize]['zoom_crop']}";
		}
		$html .= $this->Html->image($path, array('alt' => $this->data['Upload']['title']));
		
		return $html;
	}

}

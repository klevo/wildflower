<?php
App::import('Helper', 'Html');

/**
 * HTML Advanced Helper
 *
 * @package SM Events
 */
class HtmlaHelper extends HtmlHelper {
    
    function link($title, $url = null, $htmlAttributes = array(), $confirmMessage = false, $escapeTitle = true) {
        $linkUrl = parent::url($url);
        $currentUrl = $this->here;
        
        // Remove paging from currentUrl
        // @TODO if another named param goes after paging it do it's thing
        $pieces = explode('/', $currentUrl);
        $paging = end($pieces);
        if (strpos($paging, 'page:') === 0) {
            array_pop($pieces);
            $currentUrl = join('/', $pieces);
        }
        
        if (isset($htmlAttributes['strict']) and $htmlAttributes['strict']) {
            $htmlAttributes['currentOn'] = $url;
        }
        unset($htmlAttributes['strict']);
        
        $currentOverride = false;
        if (isset($htmlAttributes['currentOn']) && !is_null($htmlAttributes['currentOn'])) {
            if ($currentUrl === parent::url($htmlAttributes['currentOn'])) {
                $currentOverride = true;
            }
        }
        
        if ((strpos($currentUrl, $linkUrl) === 0 && (!isset($htmlAttributes['currentOn']) || is_null($htmlAttributes['currentOn'])))
            || ($currentOverride === true)) {
            if (!isset($htmlAttributes['class'])) {
                $htmlAttributes['class'] = '';
            }
            $classes = explode(' ', $htmlAttributes['class']);
            if (!isset($classes['current'])) {
                $classes[] = 'current';
            }
            $htmlAttributes['class'] = join(' ', $classes);
        }
        
        unset($htmlAttributes['currentOn']);
        
        return parent::link($title, $url, $htmlAttributes, $confirmMessage, $escapeTitle);
    }
    
    function dateTimePicker($field, &$form) {
        $return = '';
        
        $model = $form->params['models'][0];
        
        $dateValue = date('n/j/Y');
        if (isset($this->data[$model][$field])) {
            $dateValue = date('n/j/Y', strtotime($this->data[$model][$field]));
        }
        
        $return .= $form->input($field . 'Date', array('type' => 'text', 'size' => 10, 'value' => $dateValue, 'div' => array('class' => 'picker-date input')));
        
        $timeValue = date('g:i A');
        if (isset($this->data[$model][$field])) {
            $timeValue = date('g:i A', strtotime($this->data[$model][$field]));
        }
        
        $return .= $form->input($field . 'Time', array('type' => 'text', 'size' => 8, 'value' => $timeValue, 'div' => array('class' => 'picker-time input')));
        
        return $return;
    }
    
}

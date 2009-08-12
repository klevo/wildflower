<?php
App::import('Helper', 'Html');

/**
 * Wildflower Html Helper
 *
 * Adding some missing features to Cake's HtmlHelper.
 *
 * @package Wildflower
 */
class HtmlaHelper extends HtmlHelper {
    
    function link($title, $url = null, $htmlAttributes = array(), $confirmMessage = false, $escapeTitle = true) {
        // $parsedUrl = rtrim(parent::url($url), '/');
        //         $parsedUrl = rtrim($parsedUrl, '/index');
        //         $currentUrl = rtrim($this->here, '/');
        //         $currentUrl = rtrim($currentUrl, '/index');
        //         $linksToCurrentPage = (bool)($parsedUrl === $currentUrl);
        //         
        //         $containsCurrentPage = (bool)(strpos($currentUrl, $parsedUrl) === 0);
        //         
        //         if ($linksToCurrentPage or (!isset($htmlAttributes['strict']) and $containsCurrentPage)) {
        //             if (isset($htmlAttributes['class'])) {
        //                 $htmlAttributes['class'] = $htmlAttributes['class'] + ' current';
        //             } else {
        //                 $htmlAttributes['class'] = 'current';
        //             }
        //         }
        //         
        //         unset($htmlAttributes['strict']);
        $parsedUrl = rtrim(parent::url($url), '/');
        $parsedUrl = rtrim($parsedUrl, '/index');
        $currentUrl = rtrim($this->here, '/');
        $currentUrl = rtrim($currentUrl, '/index');
        $linksToCurrentPage = (bool)($parsedUrl === $currentUrl);
        $isPartOfUrl = (bool)(strpos($currentUrl, $parsedUrl) === 0);
        if ($linksToCurrentPage or (!isset($htmlAttributes['strict']) and $isPartOfUrl)) {
            if (isset($htmlAttributes['class'])) {
                $htmlAttributes['class'] += ' current';
            } else {
                $htmlAttributes['class'] = 'current';
            }
        }        
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

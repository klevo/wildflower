<?php
class HabtmHelper extends HtmlHelper {
    
    public $helpers = array('Html');
    public $tags = array(
        'hiddenmultiple' => '<input id="%s" type="hidden" name="data[%s][%s][]" %s />',
        'checkboxmultiple' => '<label class="checkboxMultipleLabel" for="%s"><input id="%s" type="checkbox" name="data[%s][%s][]" %s/>%s</label>');
    
    /**
     * Returns a list of checkboxes.
     *
     * @param string $fieldName Name attribute of the SELECT
     * @param array $options Array of the elements (as 'value'=>'Text' pairs)
     * @param array $selected Selected checkboxes
     * @param string $inbetween String that separates the checkboxes.
     * @param array $htmlAttributes Array of HTML options
     * @param  boolean $return         Whether this method should return a value
     * @return string List of checkboxes
     */
    function checkboxMultiple($fieldName, $options, $selected = null, $inbetween = null, $htmlAttributes = null, $return = false) {
        $this->setEntity($fieldName);
        if ($this->tagIsInvalid($this->model(), $this->field())) {
            if (isset($htmlAttributes['class']) && trim($htmlAttributes['class']) != "") {
                $htmlAttributes['class'] .= ' form_error';
            } else {
                $htmlAttributes['class'] = 'form_error';
            }
        }
        if (!is_array($options)) {
            return null;
        }    
        if (!isset($selected)) {
            $selected = $this->tagValue($fieldName);
        }
        $count = 0;
        foreach($options as $name => $title) {
            $optionsHere = $htmlAttributes;
            if (($selected !== null) && ($selected == $name)) {
                $optionsHere['checked'] = 'checked';
            } else if (is_array($selected) && array_key_exists($name, $selected)) {
                $optionsHere['checked'] = 'checked';
            }
            $optionsHere['value'] = $name;
            
            $count++;
            $boxId = $this->model() . $this->field() . $count;
            
            $checkbox[] = "<li>" . sprintf($this->tags['checkboxmultiple'], $boxId, $boxId, $this->model(), $this->field(), $this->_parseAttributes($optionsHere), $title) . "</li>\n";
        }
        $cssId = $this->model() . $this->model();
        return "\n" . sprintf($this->tags['hiddenmultiple'], $cssId, $this->model(), $this->field(), null, $title) . "\n<ul class=\"checkboxMultiple\">\n" . $this->output(implode($checkbox), $return) . "</ul>\n";
    }
    
}

<h2 class="section">Site Settings</h2>

<?php
    $session->flash();
    echo $form->create('Setting', array('action' => 'update', 'class' => 'settings_form'));
    
    // echo '<pre>';
    // foreach ($settings as $setting) {
    //     $out = "array(\n";
    //     foreach ($setting['Setting'] as $label => $value) {
    //         if (!is_numeric($value)) {
    //             $value = "'$value'";
    //         }
    //         $out .= "'$label' => $value,\n";
    //     }
    //     $out .= "),";
    //     echo $out;
    // }
    // echo '</pre>';
    
    foreach ($settings as $setting) {
        $name = "Setting.{$setting['Setting']['id']}";
        $options = array(
            'type' => $setting['Setting']['type'],
            'value' => $setting['Setting']['value'],
            'div' => array('id' => "setting-{$setting['Setting']['name']}")
        );
        
        if (!empty($setting['Setting']['description'])) {
            $setting['Setting']['description'] = r('the site root', FULL_BASE_URL, $setting['Setting']['description']);
            $options['after'] = "<p class=\"setting-desc\">{$setting['Setting']['description']}</p>";
        }
        
        if ($setting['Setting']['name'] == 'home_page_id') {
            $options['options'] = $homePageIdOptions;
            $options['escape'] = false;
        } else if ($setting['Setting']['name'] == 'email_delivery') {
            $options['options'] = array('mail' => 'Local server', 'smtp' => 'SMTP account');
            if (Configure::read('debug') > 0) {
                $options['options']['debug'] = 'Dump to screen';
            }
        } else if ($setting['Setting']['name'] == 'cache') {
            $options['options'] = array('on' => 'On', 'off' => 'Off');
        }
        
        if (empty($setting['Setting']['label'])) {
            $options['label'] = Inflector::humanize($setting['Setting']['name']);
        } else {
            $options['label'] = $setting['Setting']['label'];
        }
        
        if ($options['type'] == 'text') {
            $options['size'] = 60;
        } else if ($options['type'] == 'textbox') {
            $options['rows'] = 4;
            $options['cols'] = 58;
        } else if ($options['type'] == 'checkbox' and $options['value'] == 1) {
            $options['checked'] = true;
        }
        
        echo $form->input($name, $options);
    }
    
    echo
    '<div id="edit-buttons">',
    $form->submit('Save changes', array('div' => array('class' => 'submit save-section'))),
    '</div>',
    '<div class="cleaner"></div>',
    $form->end();
?>

<?php $partialLayout->blockStart('sidebar'); ?>
    <?php echo $this->element('../settings/_right_menu'); ?>
<?php $partialLayout->blockEnd(); ?>

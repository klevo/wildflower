<h2 class="section">Site Settings</h2>

<?php
    $session->flash();
    echo $form->create('WildSetting', array('action' => 'update', 'class' => 'horizontal-form'));
    
    foreach ($settings as $setting) {
        $name = "WildSetting.{$setting['WildSetting']['id']}";
        $options = array(
            'type' => $setting['WildSetting']['type'],
            'value' => $setting['WildSetting']['value'],
            'div' => array('id' => "setting-{$setting['WildSetting']['name']}")
        );
        
        if (!empty($setting['WildSetting']['description'])) {
            $options['after'] = "<p class=\"setting-desc\">{$setting['WildSetting']['description']}</p>";
        }
        
        if ($setting['WildSetting']['name'] == 'home_page_id') {
            $options['options'] = $homePageIdOptions;
            $options['escape'] = false;
        } else if ($setting['WildSetting']['name'] == 'email_delivery') {
            $options['options'] = array('mail' => 'Local server', 'smtp' => 'SMTP account');
            if (Configure::read('debug') > 0) {
                $options['options']['debug'] = 'Dump to screen';
            }
        } else if ($setting['WildSetting']['name'] == 'cache') {
            $options['options'] = array('on' => 'On', 'off' => 'Off');
        }
        
        if (empty($setting['WildSetting']['label'])) {
            $options['label'] = Inflector::humanize($setting['WildSetting']['name']);
        } else {
            $options['label'] = $setting['WildSetting']['label'];
        }
        
        if ($options['type'] == 'text') {
            $options['size'] = 60;
        } else if ($options['type'] == 'textbox') {
            $options['rows'] = 4;
            $options['cols'] = 58;
        }
        
        echo $form->input($name, $options);
    }
    
    echo
    $form->submit('Save changes', array('div' => array('class' => 'submit save-section'))),
    '<div class="cleaner"></div>',
    $form->end();
?>

<?php
    $session->flash();
    
    echo $form->create('WildSetting', array('action' => 'update'/* , 'enctype' => 'multipart/form-data'*/));
    
    foreach ($settings as $setting) {
        $name = "WildSetting.{$setting['WildSetting']['id']}";
        $options = array(
            'type' => $setting['WildSetting']['type'],
            'between' => '<br />',
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
        
        echo $form->input($name, $options);
    }
    
    echo
    $wild->submit('Save changes');
    $form->end();
?>

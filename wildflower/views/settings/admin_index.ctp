<?php
    $session->flash();
    
    echo $form->create('Setting', array('action' => 'update'/* , 'enctype' => 'multipart/form-data'*/));
    
    foreach ($settings as $setting) {
        $name = "Setting.{$setting['Setting']['id']}";
        $options = array(
            'type' => $setting['Setting']['type'],
            'between' => '<br />',
            'value' => $setting['Setting']['value'],
            'div' => array('id' => "setting-{$setting['Setting']['name']}")
        );
        
        if (!empty($setting['Setting']['description'])) {
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
        
        echo $form->input($name, $options);
    }
?>

<div>
    <input id="save" type="image" src="<?php echo $html->url('/css/wfadmin/img/save-button.png') ?>" tabindex="3" />
</div>
 
<?php  
    echo  
    $form->end();
?>

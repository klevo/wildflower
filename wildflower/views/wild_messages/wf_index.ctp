
<h2 class="section"><?php __('Contact form messages'); ?></h2>
<?php
    echo 
    $list->create($messages, array('model' => 'WildMessage', 'class' => 'list selectable-list', 'element' => 'admin_messages_list_item')),
    $this->element('wf_pagination');
?>  

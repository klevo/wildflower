<?php 
echo $navigation->create(array(
        'Mark as spam' => '#',
        'Delete' => '#',
        'All messages' => array('action' => 'index'),
    ), array('id' => 'sub-nav'));
?>

<table class="message-view">
    <tbody>
        <?php
            $mailto = '&lt;<a href="mailto:' . $message['Message']['email'] . '">'
                . $message['Message']['email'] . '</a>&gt;';
        ?>
        <tr><th><?php echo __('From') ?></th><td><?php echo hsc($message['Message']['name']), ' ', $mailto; ?></td></tr>
        <tr><th><?php echo __('Received') ?></th><td><?php echo $time->niceShort($message['Message']['created']) ?></td></tr>
        <tr><th><?php echo __('Phone') ?></th><td><?php echo hsc($message['Message']['phone']) ?></td></tr>
        <tr><th><?php echo __('Subject') ?></th><td><?php echo hsc($message['Message']['subject']) ?></td></tr>
        <tr><th><?php echo __('Content') ?></th><td><?php echo hsc($message['Message']['content']) ?></td></tr>
    </tbody>
</table>

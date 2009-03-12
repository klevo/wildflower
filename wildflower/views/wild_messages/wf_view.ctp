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
            $mailto = '&lt;<a href="mailto:' . $message['WildMessage']['email'] . '">'
                . $message['WildMessage']['email'] . '</a>&gt;';
        ?>
        <tr><th>From</th><td><?php echo hsc($message['WildMessage']['name']), ' ', $mailto; ?></td></tr>
        <tr><th>Received</th><td><?php echo $time->niceShort($message['WildMessage']['created']) ?></td></tr>
        <tr><th>Phone</th><td><?php echo hsc($message['WildMessage']['phone']) ?></td></tr>
        <tr><th>Subject</th><td><?php echo hsc($message['WildMessage']['subject']) ?></td></tr>
        <tr><th>Message</th><td><?php echo hsc($message['WildMessage']['content']) ?></td></tr>
    </tbody>
</table>

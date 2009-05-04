
<h2 class="section"><?php __('Contact form message from '); ?><?php echo hsc($message['WildMessage']['name']); ?></h2>

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

<?php
// @TODO quick reply form
    // echo
    //     $form->create('WildMessage', array('url' => $here)),
    //     $form->input('reply', array('type' => 'textbox')),
    //     $form->end('Send to ' . $message['WildMessage']['email']);
?>

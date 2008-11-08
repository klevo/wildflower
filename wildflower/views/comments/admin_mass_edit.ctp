<h2 class="top"><?php echo __('Latest comments'); ?></h2>

<p><?php echo $html->link(__('Mass edit', true), array('action' => 'mass_edit')) ?></p>

<?php 
    if ($session->check('Message.flash')) {
        $session->flash();
    }
    
    echo $commentsList->massEdit($comments);
    echo $navigation->paginator();
?>

<div id="sidebar">
    <?php echo $html->link(__('Comments marked as spam', true), array('action' => 'spam')) ?>
</div>
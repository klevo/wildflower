<h2 class="top">Latest comments</h2>

<p><?php echo $html->link('Mass edit', array('action' => 'mass_edit')) ?></p>

<?php 
    if ($session->check('Message.flash')) {
        $session->flash();
    }
    
    echo $commentsList->massEdit($comments);
    echo $navigation->paginator();
?>

<div id="sidebar">
    <?php echo $html->link('Comments marked as spam', array('action' => 'spam')) ?>
</div>
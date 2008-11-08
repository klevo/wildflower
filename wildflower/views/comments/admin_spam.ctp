<?php echo $this->element('admin_comments_sub_nav') ?>

<h2 class="top"><?php echo __('Spam Comments') ?></h2>

<?php 
    echo 
    $commentsList->create($comments, array('class' => 'comments-list spam-list'), true), 
    $navigation->paginator(); 
?>


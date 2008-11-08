<?php echo $this->element('admin_comments_sub_nav') ?>

<h2 class="top"><?php echo __('Approved Comments');?></h2>

<?php
    echo
    $commentsList->create($comments),
    $navigation->paginator();
?>

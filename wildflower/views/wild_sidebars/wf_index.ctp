<?php
	echo 
	$form->create('WildSidebar', array('action' => 'mass_update'));
?>

<h2 class="section"><?php __('Sidebars'); ?></h2>

<?php echo $this->element('wf_select_actions'); ?>

<ul class="list">
    <?php foreach ($sidebars as $sidebar): ?>
        <li class="">
            <span class="row-check"><?php echo $form->checkbox('id.' . $sidebar['WildSidebar']['id']) ?></span>
            <span class="title-row"><?php echo $html->link($sidebar['WildSidebar']['title'], array('action' => 'edit', $sidebar['WildSidebar']['id']), array('title' => __('Edit this sidebar', true))) ?></span>
            <span class="cleaner"></span>
        </li>
    <?php endforeach; ?>
</ul>

<?php
    echo
    $this->element('wf_select_actions'),
    $form->end();
?>


<?php $partialLayout->blockStart('sidebar'); ?>
    <li><?php echo $html->link(
        '<span>' . __('Add a new sidebar', true) . '</span>',
        array('action' => 'add'),
        array('class' => 'add', 'escape' => false)); ?>
    </li>
<?php $partialLayout->blockEnd(); ?>


    

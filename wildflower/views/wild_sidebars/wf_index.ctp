<?php
	echo 
	$form->create('WildSidebar', array('action' => 'mass_update'));
?>

<h2 class="section"><?php __('Modules'); ?></h2>

<?php $session->flash(); ?>

<h3><?php __('Sidebars'); ?></h3>

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


<h3><?php __('Navigation'); ?></h3>

<ul class="list">
<?php if (empty($menus)): ?>
    <?php echo $html->tag('p', __('No menus created.', true)); ?>
<?php else: ?>
    <?php foreach ($menus as $menu): ?>
        <li class="">
            <span class="title-row"><?php echo $html->link($menu['WildMenu']['title'], array('action' => 'edit', 'controller' => 'wild_menus', $menu['WildMenu']['id']), array('title' => __('Edit this menu', true))) ?></span>
            <span class="cleaner"></span>
        </li>
    <?php endforeach; ?> 
    </ul>
<?php endif; ?>

<?php $partialLayout->blockStart('sidebar'); ?>
    <li><?php echo $html->link(
        '<span>' . __('Add a new sidebar', true) . '</span>',
        array('action' => 'add'),
        array('class' => 'add', 'escape' => false)); ?>
    </li>    
    <?php if (Configure::read('debug') > 0): ?>
    <li><?php echo $html->link(
        '<span>' . __('Add a new menu', true) . '</span>',
        array('action' => 'add', 'controller' => 'wild_menus'),
        array('class' => 'add', 'escape' => false)); ?>
    </li>
    <?php endif; ?>
<?php $partialLayout->blockEnd(); ?>


    

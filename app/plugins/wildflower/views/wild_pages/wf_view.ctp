<?php 
    $partialLayout->switchToEditorMode();
    $partialLayout->setLayoutVar('publishedLink', $html->link(FULL_BASE_URL . $this->base . $this->data['WildPage']['url'], $this->data['WildPage']['url']));

    echo $html->link(__('Edit', true), array('action' => 'edit', $page['WildPage']['id'])); 
?>

<hr />

<div class="entry">
    <h2><?php echo hsc($page['WildPage']['title']); ?></h2>
    <?php echo $texy->process($page['WildPage']['content']); ?>
</div>

<hr />

<?php $partialLayout->blockStart('sidebar'); ?>
    <li>
        <h4>Versions</h4>

    </li>
    
    
    <li class="main_sidebar">
        <ul class="sidebar-menu-alt edit-sections-menu">
            <li><?php echo $html->link('Options <small>like status, publish date, etc.</small>', array('action' => 'options', $this->data['WildPage']['id']), array('escape' => false)); ?></li>
        </ul>
    </li>
<?php $partialLayout->blockEnd(); ?>
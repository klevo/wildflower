<?php 
    $partialLayout->switchToEditorMode();
    $partialLayout->setLayoutVar('publishedLink', $html->link(FULL_BASE_URL . $this->base . $this->data['WildPage']['url'], $this->data['WildPage']['url']));

    echo $html->link('<span>' . __('Edit this page', true) . '</span>', array('action' => 'edit', $page['WildPage']['id']), array('class' => 'button', 'escape' => false)); 
?>
<span class="cleaner"></span>
<hr />

<div class="entry">
    <h1><?php echo hsc($page['WildPage']['title']); ?></h1>
    <?php echo $texy->process($page['WildPage']['content']); ?>
</div>

<hr />

<?php $partialLayout->blockStart('sidebar'); ?>
    <li>
        <h4>Versions</h4>
        <ul>
        <?php
            foreach ($revisions as $version) {
                echo '<li>', $html->link($time->niceShort($version['WildRevision']['created']), array('action' => 'view', 'rev' => $version['WildRevision']['id'], $this->data['WildPage']['id'])), '</li>';
            }
        ?>
        </ul>
    </li>
    
    
    <li class="main_sidebar">
        <ul class="sidebar-menu-alt edit-sections-menu">
            <li><?php echo $html->link('Options <small>like status, publish date, etc.</small>', array('action' => 'options', $this->data['WildPage']['id']), array('escape' => false)); ?></li>
        </ul>
    </li>
<?php $partialLayout->blockEnd(); ?>
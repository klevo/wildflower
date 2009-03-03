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
    <li class="versions">
        <h4>Versions</h4>
        <ul>
        <?php
            $attr = array();
            foreach ($revisions as $version) {
                if (isset($this->params['named']['rev']) and $this->params['named']['rev'] == $version['WildRevision']['revision_number']) {
                    $attr['class'] = 'current';
                }
                echo '<li>', $html->link($time->niceShort($version['WildRevision']['created']), "/{$this->params['prefix']}/pages/view/{$page['WildPage']['id']}/rev:{$version['WildRevision']['revision_number']}", $attr), '</li>';
                $attr['class'] = '';
            }
        ?>
        </ul>
    </li>
    
    
    <li class="main_sidebar">
        <ul class="sidebar-menu-alt edit-sections-menu">
            <li><?php echo $html->link('Options <small>like status, publish date, etc.</small>', array('action' => 'options', $page['WildPage']['id']), array('escape' => false)); ?></li>
        </ul>
    </li>
<?php $partialLayout->blockEnd(); ?>
<?php 
    $editUrl = Router::url(array('action' => 'edit', $page['Page']['id']));
    if (isset($this->params['named']['rev'])) {
        $editUrl .= "/rev:{$this->params['named']['rev']}";
    }
?>

<a href="<?php echo hsc($editUrl); ?>" class="button"><span><?php echo hsc(__('Edit this page', true)); ?></span></a>
<span class="cleaner"></span>
<hr />

<div class="entry">
    <h1><?php echo hsc($page['Page']['title']); ?></h1>
    <?php echo $page['Page']['content']; ?>
</div>

<?php if (!empty($page['Page']['sidebar_content'])): ?>
<div id="sidebar_content">
    <?php echo $texy->process($page['Page']['sidebar_content']); ?>
</div>
<?php endif; ?>
    
<span class="cleaner"></span>

<hr />

<?php $partialLayout->blockStart('sidebar'); ?>
    <li class="versions">
        <h4>Versions</h4>
        <ul>
        <?php
            $attr = array();
            foreach ($revisions as $version) {
                if (isset($this->params['named']['rev']) and $this->params['named']['rev'] == $version['Revision']['revision_number']) {
                    $attr['class'] = 'current';
                }
                echo '<li>', $html->link($time->niceShort($version['Revision']['created']), "/{$this->params['prefix']}/pages/view/{$page['Page']['id']}/rev:{$version['Revision']['revision_number']}", $attr), '</li>';
                $attr['class'] = '';
            }
        ?>
        </ul>
    </li>
    
    <li class="main_sidebar">
        <ul class="sidebar-menu-alt edit-sections-menu">
            <li><?php echo $html->link('Options <small>like status, publish date, etc.</small>', array('action' => 'options', $page['Page']['id']), array('escape' => false)); ?></li>
        </ul>
    </li>
<?php $partialLayout->blockEnd(); ?>
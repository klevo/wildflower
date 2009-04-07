<div id="primary-content">

    <?php
        $cssClasses = array('post');
        if (isset($this->params['ChangeIndicator'])) {
            $changedId = $this->params['ChangeIndicator']['id'];
            if ($changedId == $post['WildPost']['id']) {
                array_push($cssClasses, 'changed');
                unset($this->params['ChangeIndicator']);
            }
        }
    ?>

    <?php foreach ($posts as $post) { ?>
    <div class="<?php echo join(' ', $cssClasses) ?>" id="post-<?php echo $post['WildPost']['id'] ?>">
        <h2><?php echo $html->link($post['WildPost']['title'], WildPost::getUrl($post['WildPost']['slug'])); ?></h2>
        <small class="post-date">Posted <?php echo $time->nice($post['WildPost']['created']) ?></small>
        
        <div class="entry"><?php echo $wild->processWidgets($post['WildPost']['content']); ?></div>
        
        <?php if (!empty($post['WildCategory'])): ?>
        <p class="postmeta">Posted in <?php echo $category->getList($post['WildCategory']); ?>.</p>
        <?php endif; ?>
        
        <?php echo $this->element('edit_this', array('id' => $post['WildPost']['id'])); ?>
        
    </div>
    <?php } ?>
    
    <?php echo $this->element('wf_pagination') ?>
    
</div>

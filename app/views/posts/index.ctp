<div id="primary-content">

    <?php
        $cssClasses = array('post');
        if (isset($this->params['ChangeIndicator'])) {
            $changedId = $this->params['ChangeIndicator']['id'];
            if ($changedId == $post['Post']['id']) {
                array_push($cssClasses, 'changed');
                unset($this->params['ChangeIndicator']);
            }
        }
    ?>

    <?php foreach ($posts as $post) { ?>
    <div class="<?php echo join(' ', $cssClasses) ?>" id="post-<?php echo $post['Post']['id'] ?>">
        <h2><?php echo $html->link($post['Post']['title'], "/blog/{$post['Post']['slug']}") ?></h2>
        <small class="post-date">Posted <?php echo $time->nice($post['Post']['created']) ?></small>
        
        <div class="entry">
            <?php echo $post['Post']['content'] ?>
        </div>
        
        <?php if (!empty($post['Category'])) { ?>
        <p class="postmeta">Posted in <?php echo $category->getList($post['Category']) ?>.</p>
        <?php } ?>
        
        <?php echo $this->element('edit_this', array('id' => $post['Post']['id'])) ?>
        
    </div>
    <?php } ?>
    
    <?php echo $navigation->paginator() ?>
    
</div>

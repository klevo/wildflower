<div id="primary-content">

    <?php foreach ($category['Post'] as $post) { ?>
    <div class="post" id="post-<?php echo $post['id']; ?>">
        <h2><?php echo $html->link($post['title'], "/p/{$post['slug']}") ?></h2>
        <small class="post-date">Posted <?php echo $time->nice($post['created']); ?></small>
        
        <div class="entry">
            <?php echo $post['content']; ?>
        </div>
        
        <?php if (!empty($post['Category'])) { ?>
        <p class="postmeta">Posted in <?php echo $category->getList($post['Category']); ?>.</p>
        <?php } ?>
        
        <?php echo $this->element('edit_this', array('id' => $post['id'], 'controller' => 'posts')) ?>
        
    </div>
    <?php } ?>
    
    <?php // echo $navigation->paginator($this->params); ?>
    
</div>

<?php echo $this->renderElement('sidebar'); ?>
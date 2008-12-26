<?php ob_start(); ?>
This post is <?php if ($post['WildPost']['draft']): ?>not published, therefore not visible to the public<?php else: ?>published and visible to the public at <?php echo $html->link(FULL_BASE_URL . $this->base . WildPost::getUrl($post['WildPost']['uuid']), WildPost::getUrl($post['WildPost']['uuid'])); ?><?php endif; ?>. Latest changes were made <?php echo $time->nice($post['WildPost']['updated']); ?> by <?php echo hsc($post['WildUser']['name']); ?>.
<?php $info = ob_get_clean(); ?>

<?php ob_start(); ?>
<div id="post-preview">
    <h2 class="section">Post Preview</h2>
    <object data="<?php echo $html->url(array('action' => 'wf_preview')); ?>" type="text/html"></object>
</div>

<div class="submit" id="save-preview">
    <input type="submit" value="<?php __('Preview'); ?>" />
</div>
<?php if ($post['WildPost']['draft']): ?>    
<div class="submit" id="save-draft">
    <input type="submit" value="<?php __('Save, but don\'t publish'); ?>" name="data[__save][draft]" />
</div>
<div class="submit" id="save-publish">
    <input type="submit" value="<?php __('Publish'); ?>" name="data[__save][publish]" />
</div>
<?php else: ?>
<div class="submit" id="save-draft">
    <input type="submit" value="<?php __('Save changes'); ?>" />
</div>
<?php endif; ?>
<div class="cancel-edit"> <?php __('or'); ?> <?php echo $html->link(__('Cancel and go back to all posts', true), array('action' => 'wf_index')); ?></div>
<?php $buttons = ob_get_clean(); ?>

<?php

$json = array(
    'post-info' => $info,
    'edit-buttons' => $buttons,
);

echo json_encode($json);
?>
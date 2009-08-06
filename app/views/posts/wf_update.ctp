<?php ob_start(); ?>
This post is <?php if ($post['Post']['draft']): ?>not published, therefore not visible to the public<?php else: ?>published and visible to the public at <?php echo $html->link(FULL_BASE_URL . $this->base . Post::getUrl($post['Post']['uuid']), Post::getUrl($post['Post']['uuid'])); ?><?php endif; ?>. Latest changes were made <?php echo $time->nice($post['Post']['updated']); ?> by <?php echo hsc($post['User']['name']); ?>.
<?php $info = ob_get_clean(); ?>

<?php
$json = array(
    'post-info' => $info,
    'edit-buttons' => $this->element('admin_edit_buttons', array('isDraft' => $post['Post']['draft'])),
);

echo json_encode($json);
?>
<?php ob_start(); ?>
This post is <?php if ($post['WildPost']['draft']): ?>not published, therefore not visible to the public<?php else: ?>published and visible to the public at <?php echo $html->link(FULL_BASE_URL . $this->base . WildPost::getUrl($post['WildPost']['uuid']), WildPost::getUrl($post['WildPost']['uuid'])); ?><?php endif; ?>. Latest changes were made <?php echo $time->nice($post['WildPost']['updated']); ?> by <?php echo hsc($post['WildUser']['name']); ?>.
<?php
$info = ob_get_clean();
$json = array(
    'post-info' => $info,
);

echo json_encode($json);
?>
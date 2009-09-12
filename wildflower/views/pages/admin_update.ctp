<?php ob_start(); ?>
This page is <?php if ($page['Page']['draft']): ?>not published, therefore not visible to the public<?php else: ?>published and visible to the public at <?php echo $html->link(FULL_BASE_URL . $this->base . $page['Page']['url'], $page['Page']['url']); ?><?php endif; ?>. Latest changes were made <?php echo $time->nice($page['Page']['updated']); 
if($hasUser) { 
	echo ' by ' . hsc($page['User']['name']); 
} ?>.
<?php $info = ob_get_clean(); ?>

<?php
$json = array(
    'post-info' => $info,
    'edit-buttons' => $this->element('admin_edit_buttons', array('isDraft' => $page['Page']['draft'])),
);

echo json_encode($json);
?>
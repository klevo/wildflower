<?php ob_start(); ?>
This page is <?php if ($page['WildPage']['draft']): ?>not published, therefore not visible to the public<?php else: ?>published and visible to the public at <?php echo $html->link(FULL_BASE_URL . $this->base . $page['WildPage']['url'], $page['WildPage']['url']); ?><?php endif; ?>. Latest changes were made <?php echo $time->nice($page['WildPage']['updated']); 
if($hasUser) { 
	echo ' by ' . hsc($page['WildUser']['name']); 
} ?>.
<?php $info = ob_get_clean(); ?>

<?php
$json = array(
    'post-info' => $info,
    'edit-buttons' => $this->element('wf_edit_buttons', array('isDraft' => $page['WildPage']['draft'])),
);

echo json_encode($json);
?>
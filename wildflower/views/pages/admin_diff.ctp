<h2 class="top"><?php echo __('Revisions for') . ' ' . $page['Page']['title'] ?></h2>

<?php
if (empty($versions)) {
	echo '<p>' . __('No revisions for this page yet') . '.';
} else {
	echo '<p>' . __('Click any of the revisions to load the changes inside the editor') . '.';
	echo "<ul class=\"revision-list\">\n";
    foreach ($versions as $version) {
    	echo '<li>', 
    	   $html->link("Revision {$version['Revision']['revision_number']}", 
    	       array('action' => 'diff', $version['Revision']['node_id'], $version['Revision']['id'])), 
    	       ", " . sprintf(__('saved %s by %s', true), $time->timeAgoInWords($version['Revision']['created']), $version['User']['name']) .
    	   		 ",</li>\n";    
    }
    echo "</ul>\n";
}

echo '<p>', $html->link(__('Back to page edit', true), array('action' => 'edit', $page['Page']['id']), array('class' => 'cancel')), '</p>';
?>
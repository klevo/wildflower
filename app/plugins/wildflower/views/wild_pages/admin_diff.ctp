<h2 class="top">Revisions for <?php echo $page['Page']['title'] ?></h2>

<?php
if (empty($versions)) {
	echo '<p>No revisions for this page yet.';
} else {
	echo '<p>Click any of the revisions to load the changes inside the editor.';
	echo "<ul class=\"revision-list\">\n";
    foreach ($versions as $version) {
    	echo '<li>', 
    	   $html->link("Revision {$version['Revision']['revision_number']}", 
    	       array('action' => 'diff', $version['Revision']['node_id'], $version['Revision']['id'])), 
    	       ", saved {$time->timeAgoInWords($version['Revision']['created'])} by {$version['User']['name']}",
    	   "</li>\n";    
    }
    echo "</ul>\n";
}

echo '<p>', $html->link('Back to page edit', array('action' => 'edit', $page['Page']['id']), array('class' => 'cancel')), '</p>';
?>
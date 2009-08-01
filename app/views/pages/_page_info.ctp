This page is <?php if ($this->data['Page']['draft']): ?>not published, therefore not visible to the public<?php else: ?>published and visible to the public at <?php echo $html->link(FULL_BASE_URL . $this->base . $this->data['Page']['url'], $this->data['Page']['url']); ?><?php endif; ?>. Latest changes were made <?php echo $time->nice($this->data['Page']['updated']); 
if($hasUser) { 
	echo ' by ' . hsc($this->data['User']['name']); 
} ?>.

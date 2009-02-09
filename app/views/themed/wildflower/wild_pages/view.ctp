<?php
/**
 * Wildflower generic page template.
 *
 * @package wildflower
 */
?>
<div class="page">
    <h2><?php echo $page['WildPage']['title']; ?></h2>
    
    <div class="entry">
       <?php echo $wild->renderPage($page['WildPage']['content']); ?>
    </div>
    
    <?php echo $this->element('edit_this', array('id' => $page['WildPage']['id'])) ?>
</div>

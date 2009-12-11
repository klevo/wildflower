<?php
/**
 * Wildflower generic page template.
 *
 * @package wildflower
 */
?>
<div class="page">
    <h2><?php echo $page['Page']['title']; ?></h2>
    
    <div class="entry">
       <?php echo $page['Page']['content']; ?>
    </div>
    
    <?php echo $this->element('edit_this', array('id' => $page['Page']['id'])) ?>
</div>


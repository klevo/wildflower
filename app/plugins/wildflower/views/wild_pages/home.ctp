<?php
/**
 * Wildflower loads this template for home page.
 *
 * @package wildflower
 */
?>
<div class="page">
    <h2><?php echo $page['WildPage']['title']; ?></h2>
    <div class="entry">
       <?php echo $page['WildPage']['content']; ?>
    </div>
    
    <?php echo $this->element('edit_this', array('id' => $page['WildPage']['id'])) ?>
</div>

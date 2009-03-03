<?php
/**
 * Wildflower loads this template for home page.
 *
 * @package wildflower
 */
?>
<div class="page">
    <div class="entry">
        <?php echo $texy->process($page['WildPage']['content']); ?>
    </div>
    
    <?php echo $this->element('edit_this', array('id' => $page['WildPage']['id'])) ?>
</div>

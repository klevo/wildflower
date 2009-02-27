
<div class="submit" id="save-draft">
    <input type="submit" value="<?php __('Save and preview'); ?>" />
</div>

<div class="cancel-edit"> <?php __('or'); ?> <?php echo $html->link(__('Cancel', true), array('action' => 'view', $this->data['WildPage']['id'])); ?></div>

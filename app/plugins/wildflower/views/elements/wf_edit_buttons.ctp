<div class="submit" id="save-draft">
    <input type="submit" value="<?php echo hsc(__('Save as the newest version', true)); ?>" />
</div>

<div class="cancel-edit"> <?php __('or'); ?> <?php echo $html->link(__('Cancel', true), array('action' => 'view', $this->data['WildPage']['id'])); ?></div>

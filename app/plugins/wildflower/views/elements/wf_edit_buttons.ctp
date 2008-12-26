<div class="submit" id="save-preview">
    <input type="submit" value="<?php __('Preview'); ?>" />
</div>

<?php if ($isDraft): ?>    
    
<div class="submit" id="save-draft">
    <input type="submit" value="<?php __('Save, but don\'t publish'); ?>" name="data[__save][draft]" />
</div>

<div class="submit" id="save-publish">
    <input type="submit" value="<?php __('Publish'); ?>" name="data[__save][publish]" />
</div>

<?php else: ?>
    
<div class="submit" id="save-draft">
    <input type="submit" value="<?php __('Save changes'); ?>" />
</div>

<?php endif; ?>

<div class="cancel-edit"> <?php __('or'); ?> <?php echo $html->link(__('Cancel and go back to all posts', true), array('action' => 'wf_index')); ?></div>
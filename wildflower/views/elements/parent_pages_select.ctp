<?php if (!empty($parentPages)) { ?>
<div class="input parent-page-input">
    <?php echo $form->label('parent_id', __('Parent page', true)); ?>
    <br />
    <?php echo $form->select('parent_id', $parentPages, $this->data['Page']['parent_id'], array('escape' => false), '(none)'); ?>
</div>
<?php } ?>
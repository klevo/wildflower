<h2 class="section"><?php echo hsc($page['Page']['title']), ' ', __('preview', true); ?></h2>

<iframe id="preview_frame" src="<?php echo $html->url($page['Page']['url']); ?>" height="700" frameborder="0">
    <p>Your browser does not support iframes.</p>
</iframe>

<div class="submit" id="close_preview">
    <input type="submit" value="<?php __('Edit'); ?>" name="ClosePreview" />
</div>

<?php $partialLayout->blockStart('sidebar'); ?>
    <?php echo $this->element('../pages/_page_edit_right_menu'); ?>
<?php $partialLayout->blockEnd(); ?>
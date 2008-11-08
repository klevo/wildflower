<div id="image-browser">
<?php foreach ($images as $image) { ?>
    <div class="browser-image">
        <img alt="<?php echo $image['Upload']['name'] ?>" 
            title="<?php echo __('Select this image') ?>" 
            src="<?php echo $html->url('/img/thumb/' . $image['Upload']['name'] . '/120/120/1') ?>">
    </div>
<?php } ?>
    <span class="cleaner"></span>
    <?php echo $navigation->paginator('admin_browse_images') ?>
</div>


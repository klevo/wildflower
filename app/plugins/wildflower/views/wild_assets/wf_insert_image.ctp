<div id="image-browser">
<?php foreach ($images as $image) { ?>
    <div class="browser-image">
        <img alt="<?php echo $image['WildAsset']['name'] ?>" 
            title="Select this image" 
            src="<?php echo $html->url('/img/thumb/' . $image['WildAsset']['name'] . '/120/120/1') ?>">
    </div>
<?php } ?>
    <span class="cleaner"></span>
    
    <?php $this->element('wf_pagination') ?>
    
</div>


<?php foreach ($images as $image) { ?>
<div class="browser-image"><img
	alt="<?php echo $image['Upload']['name'] ?>" title="Select this image"
	src="<?php echo $html->url('/uploads/thumbnails/' . $image['Upload']['thumbnail']) ?>">
</div>
<?php } ?>

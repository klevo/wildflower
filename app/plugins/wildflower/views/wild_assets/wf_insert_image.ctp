<?php if (empty($images)): ?>
    <p>No files uploaded yet.</p>
<?php else: ?>
    <ul>
    <?php foreach ($images as $image): ?>
        <li>
            <img alt="<?php echo $image['WildAsset']['name']; // ALT attr is used by the image insert ?>" 
                title="Click to select this image" 
                width="100" 
                height="100" 
                src="<?php echo $html->url("/wildflower/thumbnail/{$image['WildAsset']['name']}/100/100/1"); ?>">
        </li>
    <?php endforeach; ?>
    </ul>
<?php endif; ?>

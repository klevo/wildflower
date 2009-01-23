<?php if (empty($images)): ?>
    <p>No files uploaded yet.</p>
<?php else: ?>
    
    <ul class="file-list list">
    <?php foreach ($images as $file): ?>

        <li id="file-<?php echo $file['WildAsset']['id']; ?>" class="actions-handle">
            <?php 
                $label = $file['WildAsset']['title'];
                if (empty($label)) {
                    $label = $file['WildAsset']['name'];
                }
            ?>
            
    	    <img class="thumbnail" width="50" height="50" src="<?php echo $html->url("/wildflower/thumbnail/{$file['WildAsset']['name']}/50/50/1"); ?>" alt="<?php echo $file['WildAsset']['name']; ?>" />
    	    
            <h3><?php echo $html->link($label, array('action' => 'edit', $file['WildAsset']['id'])); ?></h3>
            
            <span class="row-actions"><?php echo $html->link(__('View/Download', true), WildAsset::getUploadUrl($file['WildAsset']['name']), array('class' => 'permalink', 'rel' => 'permalink', 'title' => __('View or download this file.', true))); ?></span>
            
            <span class="cleaner"></span>
        </li>
             
    <?php endforeach; ?>
    </ul>

<?php endif; ?>
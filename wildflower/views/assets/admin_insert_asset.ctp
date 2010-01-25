<li id="asset-browser" class="insert_image_sidebar">
    <h4>Insert an Asset</h4>
    
    <?php if (empty($assets)): ?>
        <p>No file Assets exist <?php echo $html->link('upload', array('controller' => 'assets', 'action' => 'index'));?>.</p>
    <?php else: ?>

        <ul class="file-list list">
        <?php foreach ($assets as $file): ?>

            <li id="file-<?php echo $file['Asset']['id']; ?>" class="actions-handle">

        	    <img class="thumbnail" width="50" height="50" src="<?php echo $html->url("/wildflower/thumbnail/{$file['Asset']['name']}/50/50/1"); ?>" alt="<?php echo $file['Asset']['name']; ?>" />

                <h3><?php echo hsc($file['Asset']['name']); ?></h3>

                <span class="row-actions"><?php echo $html->link(__('View/Download', true), Asset::getUploadUrl($file['Asset']['name']), array('class' => 'permalink', 'rel' => 'permalink', 'title' => __('View or download this file.', true))); ?></span>

                <span class="cleaner"></span>
            </li>

        <?php endforeach; ?>
        </ul>

    <?php endif; ?>
    
    <span class="cleaner"></span>
    <button id="insert_image">Insert selected image</button>
    <span class="cleaner"></span>
</li>

<!-- 
<li class="sidebar-box insert_image_sidebar">
    <?php echo $this->element('../assets/_upload_file_box'); ?>
</li>
-->

<li class="insert_asset_sidebar">
    <a class="cancel" href="Close">Close insert asset sidebar</a>
</li>

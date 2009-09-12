<li id="image-browser" class="insert_image_sidebar">
    <h4>Insert an image</h4>
    
    <?php if (empty($images)): ?>
        <p>No files uploaded yet.</p>
    <?php else: ?>

        <ul class="file-list list">
        <?php foreach ($images as $file): ?>

            <li id="file-<?php echo $file['Asset']['id']; ?>" class="actions-handle">

        	    <img class="thumbnail" width="50" height="50" src="<?php echo $html->url("/wildflower/thumbnail/{$file['Asset']['name']}/50/50/1"); ?>" alt="<?php echo $file['Asset']['name']; ?>" />

                <h3><?php echo hsc($file['Asset']['name']); ?></h3>

                <span class="row-actions"><?php echo $html->link(__('View/Download', true), Asset::getUploadUrl($file['Asset']['name']), array('class' => 'permalink', 'rel' => 'permalink', 'title' => __('View or download this file.', true))); ?></span>

                <span class="cleaner"></span>
            </li>

        <?php endforeach; ?>
        </ul>

        <?php echo $this->element('admin_pagination'); ?>

    <?php endif; ?>
    
    <div id="resize_image">
        <h5>Resize</h5>
        Width: <input type="text" id="resize_x" name="data[Resize][width]" size="4"> px&nbsp;&nbsp; Height: <input type="text" name="data[Resize][height]" id="resize_y" size="4"> px
    </div>
    
    <span class="cleaner"></span>
    <button id="insert_image">Insert selected image</button>
    <span class="cleaner"></span>
</li>

<!-- 
<li class="sidebar-box insert_image_sidebar">
    <?php echo $this->element('../assets/_upload_file_box'); ?>
</li>
-->

<li class="insert_image_sidebar">
    <a class="cancel" href="Close">Close insert image sidebar</a>
</li>

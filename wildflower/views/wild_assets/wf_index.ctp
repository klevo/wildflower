<h2 class="section">Files</h2>

<?php if (empty($files)): ?>
    <p>No files uploaded yet.</p>
<?php else: ?>
    
    <?php echo $form->create('WildAsset', array('action' => 'mass_update')); ?>
    
    <?php echo $this->element('wf_select_actions'); ?>
    
    <ul class="file-list list">
    <?php foreach ($files as $file): ?>

        <li id="file-<?php echo $file['WildAsset']['id']; ?>" class="actions-handle">
            <span class="row-check"><?php echo $form->checkbox('id.' . $file['WildAsset']['id']) ?></span>
            <?php 
                $label = $file['WildAsset']['title'];
                if (empty($label)) {
                    $label = $file['WildAsset']['name'];
                }
            ?>
            
            <a class="thumbnail" href="<?php echo $html->url(array('action' => 'edit', $file['WildAsset']['id'])); ?>">
    	        <img width="50" height="50" src="<?php echo $html->url("/wildflower/thumbnail/{$file['WildAsset']['name']}/50/50/1"); ?>" alt="<?php echo hsc($file['WildAsset']['title']); ?>" />
    	    </a>
    	    
            <h3><?php echo $html->link($label, array('action' => 'edit', $file['WildAsset']['id'])); ?></h3>
            
            <span class="row-actions"><?php echo $html->link(__('View', true), WildAsset::getUploadUrl($file['WildAsset']['name']), array('class' => '', 'rel' => 'permalink', 'title' => __('View or download this file.', true))); ?></span>
            
            <span class="cleaner"></span>
        </li>
             
    <?php endforeach; ?>
    </ul>
    
    <?php echo $this->element('wf_select_actions'); ?>
    <?php echo $form->end(); ?>

<?php endif; ?>

<?php echo $this->element('wf_pagination') ?>


<?php $partialLayout->blockStart('sidebar'); ?>
    <li class="sidebar-box">
        <?php echo $this->element('../wild_assets/_upload_file_box'); ?>
    </li>
<?php $partialLayout->blockEnd(); ?>


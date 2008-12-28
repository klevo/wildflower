<h2 class="section">Files</h2>

<?php if (empty($files)): ?>
    <p>No files uploaded yet.</p>
<?php else: ?>
    
    <?php echo $form->create('Asset', array('url' => $html->url(array('action' => 'mass_update', 'base' => false)))); ?>
    <?php echo $this->element('wf_select_actions'); ?>
    
    <ul class="file-list">
    <?php foreach ($files as $file): ?>

        <li id="file-<?php echo $file['WildAsset']['id']; ?>">
            <span class="row-check"><?php echo $form->checkbox('id.' . $file['WildAsset']['id']) ?></span>
            <?php 
                $label = $file['WildAsset']['title'];
                if (empty($label)) {
                    $label = $file['WildAsset']['name'];
                }
            ?>
            <h3><?php echo $html->link($label, array('action' => 'edit', $file['WildAsset']['id'])); ?></h3>
            <a href="<?php echo $html->url(array('action' => 'edit', $file['WildAsset']['id'])); ?>">
    	        <img width="90" height="90" src="<?php echo $html->url("/wildflower/thumbnail/{$file['WildAsset']['name']}/90/90/1"); ?>" alt="<?php echo hsc($file['WildAsset']['title']); ?>" />
    	    </a>
        </li>
             
    <?php endforeach; ?>
    </ul>
    
    <?php echo $this->element('wf_select_actions'); ?>
    <?php echo $form->end(); ?>

<?php endif; ?>

<?php echo $this->element('wf_pagination') ?>


<?php $partialLayout->blockStart('sidebar'); ?>
    <li class="sidebar-box">
        <h4><?php __('Upload a new file'); ?></h4>
        <?php
        	echo 
        	$form->create('WildAsset', array('type' => 'file', 'action' => 'wf_create')),
            $form->input('file', array('type' => 'file', 'between' => '<br />', 'label' => false)),
            //$form->input('title', array('between' => '<br />', 'label' => 'Title <small>(optional)</small>')),
            "<p><small>$uploadLimits.</small></p>",
            $form->submit('Upload file'),
            $form->end();
        ?>
    </li>
<?php $partialLayout->blockEnd(); ?>


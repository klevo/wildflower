<h2 class="section">Files</h2>

<?php if (empty($files)): ?>
    <p>No files uploaded yet.</p>
<?php else: ?>

    <ul class="file-list">
    <?php foreach ($files as $file): ?>

        <li id="file-<?php echo $file['WildAsset']['id']; ?>">
            <h3><?php echo !empty($file['WildAsset']['title']) ? hsc($file['WildAsset']['title']) : hsc($file['WildAsset']['name']); ?></h3>
            <a href="<?php echo $html->url(array('action' => 'wf_edit', $file['WildAsset']['id'])); ?>">
    	        <img width="120" height="120" src="<?php echo $html->url("/wildflower/thumbnail/{$file['WildAsset']['name']}/120/120/1"); ?>" alt="<?php echo hsc($file['WildAsset']['title']); ?>" /></a>
        </li>
             
    <?php endforeach; ?>
    </ul>

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


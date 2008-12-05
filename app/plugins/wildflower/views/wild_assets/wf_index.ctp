<div id="content">
    
    <div id="file-upload">
    	<h3>Upload a new file</h3>
    	<?php
    		echo 
    		$form->create('WildAsset', array('type' => 'file', 'action' => 'create')),
            $form->input('file', array('type' => 'file', 'between' => '<br />', 'label' => 'File')),
            "<p><small>$uploadLimits.</small></p>",
            //$form->input('title', array('between' => '<br />', 'label' => 'Title <small>(optional)</small>')),
            $wild->submit('Upload file'),
            $form->end();
    	?>
    </div>

<?php if (empty($files)): ?>
    <p>No files uploaded yet.</p>
<?php else: ?>

    <ul class="file-list">

    <?php foreach ($files as $file) { ?>

        <li id="file-<?php echo $file['WildAsset']['id']; ?>">
            <h3><?php echo !empty($file['WildAsset']['title']) ? hsc($file['WildAsset']['title']) : hsc($file['WildAsset']['name']); ?></h3>
            <a href="<?php echo $html->url(array('action' => 'wf_edit', $file['WildAsset']['id'])); ?>">
    	        <img src="<?php echo "{$this->base}/img/thumb/{$file['WildAsset']['name']}/120/120/1"; ?>" alt="<?php echo hsc($file['WildAsset']['title']); ?>" /></a>
        </li>
             
    <?php } ?>
    </ul>

<?php endif; ?>

<?php echo $this->element('wf_pagination') ?>

</div>

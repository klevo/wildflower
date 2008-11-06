<?php echo $navigation->create(array(
        'Delete' => '#Delete', 
    ), array('id' => 'file-toolbar')) ?>

<?php if (empty($files)) { ?>
    <p>No files uploaded.</p>
<?php } else { ?>

<ul class="file-list">
<?php
    $mimes = array(
        'application/zip' => 'zip',
    );
?>

<?php foreach ($files as $file) { ?>

    <?php
        $fileCssClasses = array("upload-{$file['WildAsset']['id']}");
        
        $mime = trim($file['WildAsset']['mime']);
        $imageSrc = '';
        
        if (strpos($mime, 'image') === 0) {
            $imageSrc = "{$this->base}/img/thumb/{$file['WildAsset']['name']}/120/120/1";
        } else if (isset($mimes[$mime])) {
            array_push($fileCssClasses, $mimes[$mime]);
            $imageSrc = "{$this->base}/img/mime/{$mimes[$mime]}.gif";
        } else {
        	$imageSrc = "{$this->base}/img/mime/default.gif";
        }
    ?>

    <li id="upload-<?php echo $file['WildAsset']['id'] ?>" class="<?php echo join(' ', $fileCssClasses) ?>">
        <a href="<?php echo $html->url(array('action' => 'edit', $file['WildAsset']['id'])) ?>" 
           title="Double click to edit">
	        <img src="<?php echo $imageSrc ?>"
	             alt="<?php echo $file['WildAsset']['title']; ?>" /></a>
	    <h3><?php echo $file['WildAsset']['title'] ? hsc($file['WildAsset']['title']) : hsc($file['WildAsset']['name']); ?></h3>
    </li>
             
<?php } ?>
</ul>

<?php } ?>

<?php echo $this->element('wf_pagination') ?>

<div id="file-upload">
	<h3>Upload a new file</h3>
	<?php
		echo 
		$form->create('WildAsset', array('type' => 'file', 'action' => 'create')),
        $form->input('file', array('type' => 'file', 'between' => '<br />', 'label' => 'File')),
        "<p><small>$uploadLimits.</small></p>",
        $form->input('title', array('between' => '<br />', 'label' => 'Title <small>(optional)</small>')),
        $wild->submit('Upload file'),
        $form->end();
	?>
</div>

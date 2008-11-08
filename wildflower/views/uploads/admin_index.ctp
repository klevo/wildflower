<?php echo $navigation->create(array(
        'Delete' => '#Delete', 
    ), array('id' => 'file-toolbar')) ?>

<?php if (empty($files)) { ?>
    <?php if (isset($byTag)) { ?>
    <p><?php echo __('No files tagged with') . ' "' .$byTag ?>".</p>
    <?php } else { ?>
    <p><?php echo __('No files uploaded') ?>.</p>
    <?php } ?>
<?php } else { ?>

<ul class="file-list">
<?php
    $mimes = array(
        'application/zip' => 'zip',
    );
?>

<?php foreach ($files as $file) { ?>

    <?php
        $fileCssClasses = array("upload-{$file['Upload']['id']}");
        
        $mime = trim($file['Upload']['mime']);
        $imageSrc = '';
        
        if (strpos($mime, 'image') === 0) {
            $imageSrc = "{$this->base}/img/thumb/{$file['Upload']['name']}/120/120/1";
        } else if (isset($mimes[$mime])) {
            array_push($fileCssClasses, $mimes[$mime]);
            $imageSrc = "{$this->base}/img/mime/{$mimes[$mime]}.gif";
        } else {
        	$imageSrc = "{$this->base}/img/mime/default.gif";
        }
    ?>

    <li id="upload-<?php echo $file['Upload']['id'] ?>" class="<?php echo join(' ', $fileCssClasses) ?>">
        <a href="<?php echo $html->url(array('action' => 'edit', $file['Upload']['id'])) ?>" 
           title="Double click to edit">
	        <img src="<?php echo $imageSrc ?>"
	             alt="<?php echo $file['Upload']['title']; ?>" /></a>
	    <h3><?php echo $file['Upload']['title'] ? hsc($file['Upload']['title']) : hsc($file['Upload']['name']); ?></h3>
    </li>
             
<?php } ?>
</ul>

<?php } ?>

<?php echo $navigation->paginator() ?>

<div id="file-upload">
	<h3><?php echo __('Upload a new file') ?></h3>
	<?php
		echo 
		$form->create('Upload', array('type' => 'file', 'action' => 'create')),
        $form->input('Upload.file', array('type' => 'file', 'between' => '<br />', 'label' => __('File', true))),
        "<p><small>$uploadLimits.</small></p>",
        $form->input('Upload.title', array('between' => '<br />', 'label' => __('Title <small>(optional)</small>', true))),
        $cms->submit(__('Upload', true)),
        $form->end();
	?>
</div>

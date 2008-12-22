<?php
echo $navigation->create(array(
        'All files' => array('action' => 'index')
    ), array('id' => 'sub-nav', 'class' => 'always-current'));
?>

<h2 class="top">File: <?php echo $this->data['WildAsset']['title'] ? $this->data['WildAsset']['title'] : $this->data['WildAsset']['name']; ?></h2>

<?php 
    // If file is image display it fitting the wrap
    $isImage = (strpos($this->data['WildAsset']['mime'], 'image') === 0);
    if ($isImage) {
        echo $html->image(array('action' => 'wf_thumbnail', $this->data['WildAsset']['name'], 500, 1000)),
            '<p class="image-resized-notice">This image is resized. ',
            $html->link("View the original image.", '/uploads/' . $this->data['WildAsset']['name']),
            '</p>';
    } 
    // if not an image give download link
    else {
        echo "<p>This is a file. ",
             $html->link("You can download it.", '/uploads/' . $this->data['WildAsset']['name']),
             "</p>";
    }
?>

<div id="file-upload">
    <?php
        echo 
        $form->create('WildAsset', array('type' => 'file', 'url' => $html->url(array('action' => 'wf_update', 'base' => false)))),
        $form->input('title', array('between' => '<br />', 'label' => 'Title <small>(optional)</small>')),
        '<div>',
        $form->hidden('id'),
        '</div>',
        $wild->submit('Save'),
        $form->end();
    ?>
</div>
  
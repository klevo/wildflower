<?php
echo $navigation->create(array(
        'All files' => array('action' => 'index')
    ), array('id' => 'sub-nav', 'class' => 'always-current'));
?>

<h2 class="top">File: <?php echo $this->data['Asset']['title'] ? $this->data['Asset']['title'] : $this->data['Asset']['name']; ?></h2>

<?php 
    // If file is image display it fitting the wrap
    $isImage = (strpos($this->data['Asset']['mime'], 'image') === 0);
    if ($isImage) {
        echo $html->image("/wildflower/thumbnail/{$this->data['Asset']['name']}/600/1000"),
            '<p class="image-resized-notice">This image is resized. ',
            $html->link("View the original image.", '/uploads/' . $this->data['Asset']['name']),
            '</p>';
    } 
    // if not an image give download link
    else {
        echo "<p>This is a file. ",
             $html->link("You can download it.", '/uploads/' . $this->data['Asset']['name']),
             "</p>";
    }
?>

<div id="file-upload">
    <?php
        echo 
        $form->create('Asset', array('type' => 'file', 'url' => $html->url(array('action' => 'admin_update', 'base' => false)))),
        $form->input('title', array('between' => '<br />', 'label' => 'Title <small>(optional)</small>')),
        '<div>',
        $form->hidden('id'),
        '</div>',
        $wild->submit('Save'),
        $form->end();
    ?>
</div>
  
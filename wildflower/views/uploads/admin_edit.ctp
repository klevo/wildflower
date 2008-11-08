<?php
echo $navigation->create(array(
        'Show All' => array('action' => 'index')
    ), array('id' => 'sub-nav', 'class' => 'always-current'));
?>

<h2 class="top"><?php __('File') ; ?> : <?php echo $this->data['Upload']['title'] ? $this->data['Upload']['title'] : $this->data['Upload']['name']; ?></h2>

<?php 
    // If file is image display it fitting the wrap
    $isImage = (strpos($this->data['Upload']['mime'], 'image') === 0);
    if ($isImage) {
        echo $html->image('/img/thumb/' . $this->data['Upload']['name'] . '/500/1000'),
            '<p class="image-resized-notice">' . __('This image is resized', true) . ' ',
            $html->link("View the original image.", '/uploads/' . $this->data['Upload']['name']),
            '</p>';
    } 
    // if not an image give download link
    else {
        echo "<p>This is a file. ",
             $html->link(__('You can download it', true) . '.', '/uploads/' . $this->data['Upload']['name']),
             "</p>";
    }
?>

<div id="file-upload">
    <?php
        echo 
        $form->create('Upload', array('type' => 'file', 'action' => 'update')),
        $form->input('Upload.title', array('between' => '<br />', 'label' => __('Title <small>(optional)</small>', true))),
        '<div>',
        $form->hidden('Upload.id'),
        '</div>',
        $cms->submit(__('Save', true)),
        $form->end();
    ?>
</div>
  

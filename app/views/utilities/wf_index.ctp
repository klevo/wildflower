<div id="content">
    <h3>Add content</h3>
    <?php
        echo
        $form->create('Utility', array('url' => $here)),
        $form->input('what', array(
            'type' => 'select',
            'options' => Utility::$models,
        )),
        $form->input('how_many', array('size' => 5)),
        $form->end('Proceed');
    ?>
</div>
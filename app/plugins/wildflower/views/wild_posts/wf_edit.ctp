<div id="content">
    <?php 
        $session->flash();
        
        echo 
        $form->create('WildPost', array('url' => $html->url(array('action' => 'wf_update', 'base' => false)))),
        $form->input('title', array(
            'between' => '<br />',
            'tabindex' => '1',
            'label' => __('Post Title', true),
            'div' => array('class' => 'input title-input'))),
        $form->input('content', array(
            'type' => 'textarea',
            'tabindex' => '2',
            'class' => 'tinymce',
            'rows' => '25',
            'label' => __('Content', true),
            'between' => '<br />',
            'div' => array('class' => 'input editor'))),
        '<div>',
        $form->hidden('id'),
        '</div>',
        $form->submit(__('Save, but don\'t publish', true), array('div' => array('id' => 'save-draft'))),
        $form->submit(__('Publish', true), array('div' => array('id' => 'save-publish'))), 
        $form->end();
    ?>
</div>

<ul id="sidebar">
    <li>
        <ul class="sidebar-menu">
            <li><?php echo $html->link('Title & Content', array('action' => 'wf_edit'), array('class' => 'current')); ?></li>
            <li><?php echo $html->link('Categories', array('action' => 'wf_edit_categories')); ?></li>
        </ul>
    </li>
    <li><?php echo $html->link(
        '<span>Write a new post</span>', 
        array('action' => 'add'),
        array('class' => 'add', 'escape' => false)) ?></li>
    <li>
        <?php
            echo
            $form->create('WildPost'),
            $form->input('query', array('label' => __('Find a post by typing', true))),
            $form->end();
        ?>
    </li>
</ul>

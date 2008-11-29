<div id="content">
<?php 
    echo 
    $this->element('wild_posts/form');
?>

    <div id="advanced-options">
    <?php
        echo 
        $form->input('draft', array('type' => 'select', 'between' => '<br />', 'label' => 'Status', 'options' => WildPost::getStatusOptions())),
        $form->input('description_meta_tag', array('between' => '<br />', 'type' => 'textarea', 'rows' => 6, 'cols' => 27, 'tabindex' => '4')),
        $form->input('slug', array('between' => '<br />', 'label' => 'URL slug', 'size' => 30)),
        $form->input('created', array('between' => '<br />'));
    ?>

        <!-- <p><?php echo $html->link('Delete this post', 
                    array('action' => 'delete', $this->data['WildPost']['id']), 
                    array('tabindex' => '7', 'class' => 'delete-one', 'rel' => 'post')); ?></p>  -->
    </div>

    <div id="sidebar-editor">
        <?php
            echo $form->input('sidebar_content', array(
                'type' => 'textarea',
                'class' => 'fck',
                'div' => array('class' => 'input editor')));
        ?>
    </div>

    <?php echo $this->element('admin_revision_list') ?>
    
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

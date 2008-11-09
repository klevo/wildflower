<div id="content">
<?php 
    echo 
    $this->element('wild_posts/form');
?>
</div>


<ul id="sidebar">
    <li>
        <ul>
            <li><?php echo $html->link('Title & Content', array('action' => 'wf_edit')) ?></li>
            <li><?php echo $html->link('Categories', array('action' => 'wf_edit_categories')) ?></li>
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
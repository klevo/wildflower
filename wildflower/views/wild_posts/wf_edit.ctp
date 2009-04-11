<?php 
    if ($session->check('Message.flash')) {
        $session->flash();
    }

    echo 
    $form->create('WildPost', array('url' => $html->url(array('action' => 'wf_update', 'base' => false)), 'class' => 'editor_form')),
    $form->input('title', array(
        'label' => __('Post title', true)
    )),
    $form->input('content', array(
        'type' => 'textarea',
        'class' => 'tinymce',
        'rows' => '25',
        'label' => __('Body', true),
        'div' => array('class' => 'input editor')
    )),
    '<div>',
    $form->hidden('id'),
    $form->hidden('draft'),
    $form->hidden('WildCategory.WildCategory'),
    '</div>';
?>
    
<div id="edit-buttons">
    <?php echo $this->element('wf_edit_buttons'); ?>
</div>

<?php echo $form->end(); ?>
    

<?php $partialLayout->blockStart('sidebar'); ?>
    <li class="main_sidebar">
        <?php echo $html->link(
            '<span>Write a new post</span>', 
            array('action' => 'wf_create'),
            array('class' => 'add', 'escape' => false)); ?>
    </li>
    <li class="main_sidebar category_sidebar">
        <?php echo $form->input('category_id', array('type' => 'select', 'options' => $categories, 'label' => __('Category', true), 'empty' => '(no category)', 'value' => $categoryId)); ?>
    </li>
    <li class="main_sidebar">
        <ul class="sidebar-menu-alt edit-sections-menu">
            <li><?php echo $html->link('Options <small>like status, publish date, etc.</small>', array('action' => 'options', $this->data['WildPost']['id']), array('escape' => false)); ?></li>
            <li><?php echo $html->link('Browse older versions', '#Revisions', array('rel' => 'post-revisions')); ?></li>
            <li><?php echo $html->link("Comments ({$this->data['WildPost']['wild_comment_count']})", array('action' => 'comments', $this->data['WildPost']['id'])); ?></li>
        </ul>
    </li>
<?php $partialLayout->blockEnd(); ?>

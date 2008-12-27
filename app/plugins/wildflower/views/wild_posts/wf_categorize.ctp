<?php 
    $session->flash();
    
    echo 
    $form->create('WildPost', array('url' => $html->url(array('action' => 'wf_update', 'base' => false)))),
    '<div>',
    $form->hidden('id'),
    $form->hidden('draft'),
    '</div>';
?>

<h2 class="section">Post "<?php echo hsc($this->data['WildPost']['title']); ?>" under following categories:</h2>
<ul class="category-list checkbox-list">
<?php foreach ($categories as $id => $label): ?>
    <?php $checked = in_array($id, $inCategories) ? ' checked="checked"' : ''; ?>
    <li>
        <input id="WildCategoryWildCategory<?php echo $id ?>" type="checkbox" value="<?php echo $id ?>" name="data[WildCategory][WildCategory][]"<?php echo $checked ?> />
        <label for="WildCategoryWildCategory<?php echo $id ?>"><?php echo hsc($label) ?></label>
    </li>
<?php endforeach; ?>
</ul>

<div class="submit save-section">
    <input type="submit" value="<?php __('Save categories'); ?>" />
</div>
<div class="cancel-edit cancel-section"> <?php __('or'); ?> <?php echo $html->link(__('Cancel and go back to post edit', true), array('action' => 'edit', $this->data['WildPost']['id'])); ?></div>

<?php echo $form->end(); ?>

<p class="post-info">
    This post is <?php if ($isDraft): ?>not published, therefore not visible to the public<?php else: ?>published and visible to the public at <?php echo $html->link(FULL_BASE_URL . $this->base . WildPost::getUrl($this->data['WildPost']['uuid']), WildPost::getUrl($this->data['WildPost']['uuid'])); ?><?php endif; ?>. Latest changes were made <?php echo $time->nice($this->data['WildPost']['updated']); ?> by <?php echo hsc($this->data['WildUser']['name']); ?>.
</p>


<?php $partialLayout->blockStart('sidebar'); ?>
    <li class="sidebar-box">
        <h4>Editing categories for post...</h4>
        <?php echo $html->link($this->data['WildPost']['title'], array('action' => 'edit', $this->data['WildPost']['id']), array('class' => 'edited-item-link')); ?>
    </li>
    <li id="add-category-box" class="sidebar-box">
        <h4>Add a new category</h4>
        <?php
            $createCategoryUrl = $html->url(array('controller' => 'wild_categories', 'action' => 'create'));
            echo
            $form->create('WildCategory', array('url' => $createCategoryUrl)),
            $form->input('WildCategory.title', array('between' => '<br />')),
            $form->input('WildCategory.parent_id', array('between' => '<br />', 'options' => $categories, 'empty' => '(none)')),
            $form->end('Add this category');
        ?>
    </li>
<?php $partialLayout->blockEnd(); ?>

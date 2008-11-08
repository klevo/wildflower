<h2 class="top"><?php echo $title ?></h2>

<?php 
echo $form->create($model, array('action' => 'search', 'class' => 'search')),
    $form->input('query', array('label' => '<span>' . __('Type to search', true) . '</span>', 'between' => ' ', 'class' => 'search-query'), null, null, false),
    $form->end(__('Search', true));
?>

<div class="list-toolbar">
    <div>
        <ul>
            <li><a class="delete" href="#Delete" title="Delete selected pages"><?php echo __('Delete') ?></a></li>
            <li><a class="publish" href="#Publish"><?php echo __('Publish') ?></a></li>
            <li><a class="draft" href="#Draft"><?php echo __('Draft') ?></a></li>
        </ul>
    </div>
</div>
    

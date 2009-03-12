<h2 class="top"><?php echo $title ?></h2>

<?php 
echo $form->create($model, array('action' => 'search', 'class' => 'search')),
    $form->input('query', array('label' => '<span>Type to search</span>', 'between' => ' ', 'class' => 'search-query'), null, null, false),
    $form->end('Search');
?>

<div class="list-toolbar">
    <div>
        <ul>
            <li><a class="delete" href="#Delete" title="Delete selected pages">Delete</a></li>
            <li><a class="publish" href="#Publish">Publish</a></li>
            <li><a class="draft" href="#Draft">Draft</a></li>
        </ul>
    </div>
</div>
    

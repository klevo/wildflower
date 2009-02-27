<div class="actions-handle">
    <span class="row-check"><?php echo $form->checkbox('id.' . $data['WildPage']['id']) ?></span>
    <?php
        $tree->addItemAttribute('id', 'page-' . $data['WildPage']['id']);
        $tree->addItemAttribute('class', 'level-' . $depth);
        if (ListHelper::isOdd()) {
            $tree->addItemAttribute('class', 'odd');
        }
        
        // Draft status        
        if ($data['WildPage']['draft']) {
            echo '<small class="draft-status">(', __('Draft', true), ')</small> ';
        }
    
        echo $html->link($data['WildPage']['title'], array('action' => 'view', $data['WildPage']['id']), array('title' => 'Edit this page.')); 
    ?>
    <span class="row-actions"><?php echo $html->link('View', $data['WildPage']['url'], array('class' => '', 'rel' => 'permalink', 'title' => 'View this page.')) ?></span>
    <span class="cleaner"></span>
</div>
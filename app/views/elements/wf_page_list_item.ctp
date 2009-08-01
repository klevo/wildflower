<div class="actions-handle">
    <span class="row-check"><?php echo $form->checkbox('id.' . $data['Page']['id']) ?></span>
    <?php
        $tree->addItemAttribute('id', 'page-' . $data['Page']['id']);
        $tree->addItemAttribute('class', 'level-' . $depth);
        if (ListHelper::isOdd()) {
            $tree->addItemAttribute('class', 'odd');
        }
        
        // Draft status        
        if ($data['Page']['draft']) {
            echo '<small class="draft-status">(', __('Draft', true), ')</small> ';
        }
    
        echo $html->link($data['Page']['title'], array('action' => 'edit', $data['Page']['id']), array('title' => 'Edit this page.')); 
    ?>
    <span class="row-actions"><?php echo $html->link('View', $data['Page']['url'], array('class' => '', 'rel' => 'permalink', 'title' => 'View this page.')) ?></span>
    <span class="cleaner"></span>
</div>
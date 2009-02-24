<?php 
    echo 
    $form->create('WildPage', array('url' => $html->url(array('action' => 'update', 'base' => false)), 'class' => 'horizontal-form')),
    '<div>',
    $form->hidden('id'),
    $form->hidden('draft'),
    '</div>';
?>

<h2 class="section">Versions of page <?php echo $html->link($this->data['WildPage']['title'], array('action' => 'edit', $this->data['WildPage']['id'])); ?></h2>



<?php $partialLayout->blockStart('sidebar'); ?>
    <li class="sidebar-box">
        <h4>Viewing version history of a page</h4>
        <?php echo $html->link($this->data['WildPage']['title'], array('action' => 'edit', $this->data['WildPage']['id']), array('class' => 'edited-item-link')); ?>
    </li>
    <li>Go to all pages</li>
<?php $partialLayout->blockEnd(); ?>
<?php 
    echo 
    $form->create('WildPage', array('url' => $html->url(array('action' => 'update', 'base' => false)), 'class' => 'horizontal-form')),
    '<div>',
    $form->hidden('id'),
    $form->hidden('draft'),
    '</div>';
?>

<h2 class="section">Versions of page <?php echo $html->link($this->data['WildPage']['title'], array('action' => 'edit', $this->data['WildPage']['id'])); ?></h2>

<?php 
    if (!empty($revisions)) {
        echo 
        '<ul id="revisions" class="list revision-list">';

        $first = '<span class="current-revision">&mdash;current version</span>';
        foreach ($revisions as $version) {
            $attr = '';
            if (ListHelper::isOdd()) {
                $attr = ' class="odd"';
            }
            echo 
            "<li$attr>",
            '<div class="list-item">',
            $html->link("{$time->niceShort($version['WildRevision']['created'])}",
                array('action' => 'wf_edit', $version['WildRevision']['node_id'], $first ? null : $version['WildRevision']['revision_number']), null, null, false),
            "<small>$first</small>",
            '</div>',
            '</li>';
            $first = '';
        }
        echo '</ul>';
    } else {
        echo "<p id=\"revisions\">No revisions yet.</p>";
    }
?>        


<?php $partialLayout->blockStart('sidebar'); ?>
    <li class="sidebar-box">
        <h4>Viewing version history of a page</h4>
        <?php echo $html->link($this->data['WildPage']['title'], array('action' => 'edit', $this->data['WildPage']['id']), array('class' => 'edited-item-link')); ?>
    </li>
    <li>Go to all pages</li>
<?php $partialLayout->blockEnd(); ?>
<?php 
    echo 
    $form->create('Page', array('url' => $html->url(array('action' => 'update', 'base' => false)), 'class' => 'horizontal-form')),
    '<div>',
    $form->hidden('id'),
    $form->hidden('draft'),
    '</div>';
?>

<h2 class="section">Versions of page <?php echo $html->link($this->data['Page']['title'], array('action' => 'edit', $this->data['Page']['id'])); ?></h2>

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
            $revParam = $version['Revision']['revision_number'];
            echo 
            "<li$attr>",
            '<div class="list-item">',
            $html->link("{$time->niceShort($version['Revision']['created'])}",
                "/" . Configure::read('Routing.admin') . "/pages/edit/{$version['Revision']['node_id']}/rev:$revParam"),
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
    <?php echo $this->element('../pages/_page_edit_right_menu'); ?>
<?php $partialLayout->blockEnd(); ?>
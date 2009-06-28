<?php echo $navigation->create(array(
        'Delete' => '#Delete',
        'Approved' => array('action' => 'wf_index'),
        'Spam' => array('action' => 'wf_spam'),
    ), array('id' => 'list-toolbar')) ?>

<h2>Spam comments</h2>

<?php
    // The list node
    function listItemCallback($comment, $html) {
        $editLink = $html->link($comment['WildComment']['name'] . ' on ' . $comment['WildPost']['title'], 
           array('action' => 'wf_edit', $comment['WildComment']['id']),
           array('title' => 'Edit'));
        $actions = '';/* '<span class="actions">'
            . $html->link('View', '/' . WILDFLOWER_POSTS_INDEX . "/{$comment['WildComment']['slug']}")
            . '</span>';*/

    	$posted = date('j. M y', strtotime($comment['WildComment']['created']));
    
        return '<div class="list-item"><small class="post-date">' . $posted . '</small> ' . $editLink . '</div>';
    }

    echo
    $list->create($comments, array('model' => 'WildComment', 'class' => 'list selectable-list')),
    $this->element('wf_pagination');
?>


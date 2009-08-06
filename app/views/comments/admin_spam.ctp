<?php echo $navigation->create(array(
        'Delete' => '#Delete',
        'Approved' => array('action' => 'admin_index'),
        'Spam' => array('action' => 'admin_spam'),
    ), array('id' => 'list-toolbar')) ?>

<h2>Spam comments</h2>

<?php
    // The list node
    function listItemCallback($comment, $html) {
        $editLink = $html->link($comment['Comment']['name'] . ' on ' . $comment['Post']['title'], 
           array('action' => 'admin_edit', $comment['Comment']['id']),
           array('title' => 'Edit'));
        $actions = '';/* '<span class="actions">'
            . $html->link('View', '/' . WILDFLOWER_POSTS_INDEX . "/{$comment['Comment']['slug']}")
            . '</span>';*/

    	$posted = date('j. M y', strtotime($comment['Comment']['created']));
    
        return '<div class="list-item"><small class="post-date">' . $posted . '</small> ' . $editLink . '</div>';
    }

    echo
    $list->create($comments, array('model' => 'Comment', 'class' => 'list selectable-list')),
    $this->element('admin_pagination');
?>


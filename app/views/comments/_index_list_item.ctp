<div class="actions-handle">
    <span class="row-check"><?php echo $form->checkbox('id.' . $data['Comment']['id']) ?></span>
    <?php
        $tree->addItemAttribute('id', 'comment-' . $data['Comment']['id']);
        $tree->addItemAttribute('class', 'level-' . $depth);
        if (ListHelper::isOdd()) {
            $tree->addItemAttribute('class', 'odd');
        }
        
        // Draft status        
        if ($data['Comment']['spam']) {
            echo '<small class="draft-status">(', __('SPAM!', true), ')</small> ';
        }
    ?>
    
    <div class="comment_meta">
    <?
        echo 
        $html->link(
            $data['Comment']['name'], 
            'mailto:' . $data['Comment']['email']
        ),
        __(' posted ', true),
        $time->niceShort($data['Comment']['created']);
        
        if (!empty($data['Comment']['url'])) {
            echo '&nbsp;&nbsp;&nbsp;&nbsp;', $html->link($data['Comment']['url'], $data['Comment']['url']);
        }
    ?>
    </div>
    
    <div class="comment_entry inplace-edit"><?php echo $textile->format($data['Comment']['content']) ?></div>
    
    <small class="view_comment_post">
        <?php 
            echo
            $html->link(
                'View post', 
                Post::getUrl($data['Post']['slug'])
            ); 
        ?>
    </small>
    
    <span class="cleaner"></span>
</div>
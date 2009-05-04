<div class="actions-handle">
    <span class="row-check"><?php echo $form->checkbox('id.' . $data['WildComment']['id']) ?></span>
    <?php
        $tree->addItemAttribute('id', 'comment-' . $data['WildComment']['id']);
        $tree->addItemAttribute('class', 'level-' . $depth);
        if (ListHelper::isOdd()) {
            $tree->addItemAttribute('class', 'odd');
        }
        
        // Draft status        
        if ($data['WildComment']['spam']) {
            echo '<small class="draft-status">(', __('SPAM!', true), ')</small> ';
        }
    ?>
    
    <div class="comment_meta">
    <?
        echo 
        $html->link(
            $data['WildComment']['name'], 
            'mailto:' . $data['WildComment']['email']
        ),
        __(' posted ', true),
        $time->niceShort($data['WildComment']['created']);
        
        if (!empty($data['WildComment']['url'])) {
            echo '&nbsp;&nbsp;&nbsp;&nbsp;', $html->link($data['WildComment']['url'], $data['WildComment']['url']);
        }
    ?>
    </div>
    
    <div class="comment_entry inplace-edit"><?php echo $textile->format($data['WildComment']['content']) ?></div>
    
    <small class="view_comment_post">
        <?php 
            echo
            $html->link(
                'View post', 
                WildPost::getUrl($data['WildPost']['slug'])
            ); 
        ?>
    </small>
    
    <span class="cleaner"></span>
</div>
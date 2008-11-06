    <div id="sidebar">
        
        <div class="post-options">
        <?php 
            echo $this->element('post_category_select'),
                 $html->link('Advanced options', '#PageAdvancedOptions', array('class' => 'show-advanced-options'));
        ?>

            <div class="advanced-options">
            <?php 
                echo $form->input('description_meta_tag', array('between' => '<br />', 'type' => 'textarea', 'rows' => 2, 'cols' => 8)),
                     $form->input('keywords_meta_tag', array('between' => '<br />', 'type' => 'text')),     
                     $form->input('slug', array('between' => '<br />', 'label' => 'URL slug')),
                     $form->input('created', array('between' => '<br />'));
            ?>
            </div>

        </div>
        
        <div class="sidebar-container"> 
        <?php 
            echo $form->input('query', array('label' => 'Find a post by typing something', 'class' => 'search-query'));
        ?>
        </div>
        
        <div class="sidebar-container">
            <?php echo $this->element('add_new_button') ?>
        </div>
        
        <?php echo $postsList->createSidebar($sidebarPosts) ?>
        
    </div>
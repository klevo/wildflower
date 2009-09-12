<h2 class="section"><?php echo __('Category:', true); ?> <?php echo hsc($this->data['Category']['title']); ?></h2>

<p><?php __('This category is required for proper functioning of the site. You can\'t edit or delete it.'); ?></p>

<p class="cancel-edit"><?php echo $html->link(__('Cancel', true), array('action' => 'index')); ?></p>

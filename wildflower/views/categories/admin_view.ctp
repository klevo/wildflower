<h2><?php echo __('Viewing') ?> <em><?php echo $category['Category']['title']; ?></em> <?php echo __('category') ?></h2>

<?php if (!empty($category['Category']['description'])) { ?>
<h5><?php echo __('Description') ?> :</h5>
<p><?php echo $category['Category']['description']; ?></p>
<?php } ?>

<?php echo $html->link('Edit this category', array('action' => 'edit', $category['Category']['id'])); ?>
<h3><?php echo __('Posts in this category') ?> </h3>
<ul>
<?php foreach ($category['Post'] as $post) { ?>
    <li><?php echo $html->link($post['title'], array('action' => 'edit', 'controller' => 'posts', $post['id'])); ?></li>
<?php } ?>
</ul>

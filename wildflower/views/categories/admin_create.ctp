<?php ob_start(); ?>
    <?php echo $tree->generate($categoriesForTree, array('model' => 'Category', 'class' => 'category-list checkbox-list', 'element' => '../categories/list_item', 'inCategories' => $inCategories)); ?>
<?php $list = ob_get_clean(); ?>

<?php
$json = array(
    'list' => $list,
);

echo json_encode($json);
?>
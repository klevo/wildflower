<?php ob_start(); ?>
    <?php echo $tree->generate($categoriesForTree, array('model' => 'WildCategory', 'class' => 'category-list checkbox-list', 'element' => '../wild_categories/list_item', 'inCategories' => $inCategories)); ?>
<?php $list = ob_get_clean(); ?>

<?php
$json = array(
    'list' => $list,
);

echo json_encode($json);
?>
<?php ob_start(); ?>
<li>
    <input id="WildCategoryWildCategory<?php echo $category['WildCategory']['id'] ?>" type="checkbox" value="<?php echo $category['WildCategory']['id'] ?>" name="data[WildCategory][WildCategory][]" checked="checked" />
<label for="WildCategoryWildCategory<?php echo $category['WildCategory']['id'] ?>"><?php echo hsc($category['WildCategory']['title']); ?></label>
</li>
<?php $listItem = ob_get_clean(); ?>

<?php
$json = array(
    'category-list-item' => $listItem,
);

echo json_encode($json);
?>
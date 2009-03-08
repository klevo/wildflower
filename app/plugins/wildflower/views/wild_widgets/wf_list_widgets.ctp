<li class="insert_widget_sidebar">
    <h4>Insert a widget</h4>
    <p>Widgets are small elements providing various functionality. You can insert them into any page or post.</p>

    <ul class="widget_list">
        <?php foreach ($widgets as $widget): ?>
            <li><a href="#Insert<?php echo hsc($widget['href']); ?>" rel="<?php echo hsc($widget['id']); ?>"><?php echo hsc($widget['name']); ?></a></li>
        <?php endforeach; ?>
    </ul>
</li>

<?php if (isset($results) && !empty($results)): ?>
<ul id="sidebar-search-results">
<?php
	foreach ($results as $item) {
		$model = $controller = '';
		if (isset($item['Post'])) {
			$model = 'Post';
			$controller = 'posts';
		} else if (isset($item['Page'])) {
			$model = 'Page';
			$controller = 'pages';
		} else {
			continue;
		}
		
		$url = array('controller' => $controller, 'action' => 'admin_edit', $item[$model]['id']);
		echo '<li>' . $html->link($item[$model]['title'], $url) . '</li>';
	}
?>
</ul>
<?php else: ?>
    <div id="sidebar-search-results" class="nomatch">No matches</div>
<?php endif; ?>

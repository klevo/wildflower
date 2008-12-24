<?php if (isset($results) && !empty($results)) { ?>
<ul id="sidebar-search-results">
<?php
	foreach ($results as $item) {
		$model = $controller = '';
		if (isset($item['WildPost'])) {
			$model = 'WildPost';
			$controller = 'wild_posts';
		} else if (isset($item['WildPage'])) {
			$model = 'WildPage';
			$controller = 'wild_pages';
		} else {
			continue;
		}
		
		$url = array('controller' => $controller, 'action' => 'wf_edit', $item[$model]['id']);
		echo '<li>' . $html->link($item[$model]['title'], $url) . '</li>';
	}
?>
</ul>
<?php } else { ?>
    <div id="sidebar-search-results" class="nomatch">No matches</div>
<?php } ?>

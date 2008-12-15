<?php
	echo $rss->items($posts, 'transformRSS');

	function transformRSS($element) {
		return array(
			'guid'  => '/news/' . $element['WildPost']['slug'],
			'author' => '',
			'pubDate' => $element['WildPost']['created'],
			'description' => $element['WildPost']['content'],
			'title' => $element['WildPost']['title'],
			'link' => 'http://'.$_SERVER['HTTP_HOST'].'/p/'.$element['WildPost']['uuid'],
		);
	}
?>
<?php
	echo $rss->items($posts, 'transformRSS');

	function transformRSS($element) {
	    $fullPostUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/' . Configure::read('Wildflower.postsParent') . '/' . $element['WildPost']['uuid'];
		return array(
			'guid'  => $fullPostUrl,
			'author' => hsc($element['WildUser']['email']),
			'pubDate' => $element['WildPost']['created'],
			'description' => $element['WildPost']['content'],
			'title' => hsc($element['WildPost']['title']),
			'link' => $fullPostUrl,
		);
	}

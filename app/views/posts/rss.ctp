<?php
	echo $rss->items($posts, 'transformRSS');

	function transformRSS($element) {
	    $fullPostUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/' . Configure::read('Wildflower.postsParent') . '/' . $element['Post']['uuid'];
		return array(
			'guid'  => $fullPostUrl,
			'author' => hsc($element['User']['email']),
			'pubDate' => $element['Post']['created'],
			'description' => $element['Post']['content'],
			'title' => hsc($element['Post']['title']),
			'link' => $fullPostUrl,
		);
	}

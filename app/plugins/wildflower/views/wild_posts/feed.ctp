<rss version="2.0">
	<?php echo $rss->channel(null, array('title' => $siteName, 'description' => 'TODO')); ?>
	<?php
	foreach ($posts as $post) {
		echo $rss->item(null, array(
		  'title' => $post['Post']['title'],
		  'author' => '',
		  'pubDate' => $post['Post']['created'],
		  'description' => $post['Post']['content'],
		  'guid' => '/news/' . $post['Post']['slug']));
	}
	?>
	</channel>
</rss>
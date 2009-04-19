<?php 
    $editUrl = Router::url(array('action' => 'edit', $page['WildPage']['id']));
    if (isset($this->params['named']['rev'])) {
        $editUrl .= "/rev:{$this->params['named']['rev']}";
    }
?>

<a href="<?php echo hsc($editUrl); ?>" class="button"><span><?php echo hsc(__('Edit this page', true)); ?></span></a>
<span class="cleaner"></span>
<hr />

<iframe src="<?php echo $html->url($page['WildPage']['url']); ?>" width="100%" height="700" frameborder="0">
    <p>Your browser does not support iframes.</p>
</iframe>

<hr />

<?php $partialLayout->blockStart('sidebar'); ?>
    <li></li>
<?php $partialLayout->blockEnd(); ?>
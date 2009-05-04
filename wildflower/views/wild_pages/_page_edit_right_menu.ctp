<li class="right_menu">
    <ul>
        <li><strong><?php echo $htmla->link('All Pages', array('action' => 'index', $this->params['pass'][0])); ?></strong></li>
        <li><?php echo $htmla->link('Title & body', array('action' => 'edit', $this->params['pass'][0])); ?></li>
        <li><?php echo $htmla->link('History of changes', array('action' => 'versions', $this->params['pass'][0])); ?></li>
        <li><?php echo $htmla->link('Address, dates and other options', array('action' => 'options', $this->params['pass'][0])); ?></li>
    </ul>
</li>
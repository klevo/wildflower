<?php
$debugLevel = Configure::read('debug');
if ($debugLevel > 0) { ?>
    <div class="wilflower-in-debug" style="color:red;">
        <small>Debug mode <?php echo $debugLevel ?>. In production turn this to <em>0</em> in <em>/app/config/core.php</em>.</small>
    </div>
<?php } ?>

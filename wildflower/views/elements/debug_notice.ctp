<?php
$debugLevel = Configure::read('debug');
if ($debugLevel > 0) { ?>
    <div class="wilflower-in-debug" style="background:#eee;font-size:14px;color:#000;
        text-align:left;width:300px;padding:3px;margin:15px auto;">
        <h6 style="margin:0 4px;font-size:14px;"><?php echo __('Site is in debug mode') . ' #' . $debugLevel ?></h6>
        <p style="background:#fff;color:#000;padding:3px;">
            <? echo __('In production turn this to <em>0</em> in <em>/app/config/core.php') ?></em></p>
    </div>
<?php } ?>

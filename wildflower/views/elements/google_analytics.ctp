<?php
if (Configure::read('debug') < 1) {
    echo Configure::read('Wildflower.settings.google_analytics_code');
} else {
	echo '<!-- Google Analytic turned off in debug mode. -->';
}
?>
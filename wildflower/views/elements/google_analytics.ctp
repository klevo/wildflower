<?php
if (Configure::read('debug') < 1) {
    echo Configure::read('AppSettings.google_analytics_code');
} else {
	echo '<!-- Google Analytic turned off in debug mode. -->';
}
?>
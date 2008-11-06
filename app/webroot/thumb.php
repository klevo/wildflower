<?php
/**
 * Thumbnail generator and resizer
 * 
 * Outputs resized .jpgs of the specified source image.
 * Call /img/thumb/source-image.gif/200/200/1 to get a 
 * 200x200 px zoom cropped thumbnail.
 * Generated images are cached in /app/tmp/thumbnails folder.
 */

define('DS', DIRECTORY_SEPARATOR);

function renderJpeg($cachedFilePath) {
    // Render cached image
    header("Content-Type: image/jpeg");
    
    $fileSize = filesize($cachedFilePath);
    header("Content-Length: $fileSize");
    
    $cache = fopen($cachedFilePath, 'r');
    fpassthru($cache);
    fclose($cache);
    
    exit;
}

$url = $_GET['url'];
if (empty($url)) {
	exit('Thumbnail generator: No parameters specified.');
}

// Parse the url
$params = explode('/', $url);
if (count($params) < 1) {
	exit('Thumbnail generator: No parameters specified.');
}

$defaultParameters = array(
    'source' => null,
    'width' => 720,
    'height' => 720,
    'zoom_crop' => 0
);

$i = 0;
$finalParams = array();
foreach ($defaultParameters as $param => $value) {
	$finalParams[$param] = isset($params[$i]) ? $params[$i] : $value;
	$i++;
}

/**
 * Sanitize parameters. 
 * 
 * If you need to generate bigger images, replace
 * the numbers.
 */
$finalParams['source'] = str_replace('..', '', $finalParams['source']);

$finalParams['width'] = intval($finalParams['width']);

if ($finalParams['width'] > 2560) {
	$finalParams['width'] = 2560;
}

$finalParams['height'] = intval($finalParams['height']);
if ($finalParams['height'] > 1600) {
	$finalParams['height'] = 1600;
}

$cachedFileName = implode('_', $finalParams) . '.jpg';
$cacheDir = dirname(__FILE__) . DS . '..' . DS . 'tmp' . DS . 'thumbnails';
$cachedFilePath = $cacheDir . DS . $cachedFileName;

$refreshCache = false;
$cacheFileExists = file_exists($cachedFilePath);
if ($cacheFileExists) {
	$cacheTimestamp = filemtime($cachedFilePath);
	$cachetime = 60 * 60 * 24 * 14; // 14 days
	$border = $cacheTimestamp + $cachetime;
	$now = time();
	if ($now > $border) {
		$refreshCache = true;
	}
}

if ($cacheFileExists && !$refreshCache) {
	renderJpeg($cachedFilePath);
} else {
	// Create cache and render it
	$sourceFile = dirname(__FILE__) . DS . 'uploads' . DS . $finalParams['source'];
	if (!file_exists($sourceFile)) {
		exit("Thumbnail generator: Source file $sourceFile does not exists.");
	}

	require_once(dirname(__FILE__) . DS . '..' . DS . 'vendors' . DS . 'phpThumb' . DS . 'phpthumb.class.php');

	$phpThumb = new phpThumb();

	$phpThumb->setSourceFilename($sourceFile);

	$phpThumb->setParameter('config_output_format', 'jpeg');

	$phpThumb->setParameter('w', intval($finalParams['width']));
	$phpThumb->setParameter('h', intval($finalParams['height']));
	$phpThumb->setParameter('zc', intval($finalParams['zoom_crop']));

	if ($phpThumb->GenerateThumbnail()) {
		$phpThumb->RenderToFile($cachedFilePath);
		renderJpeg($cachedFilePath);
	} else {
		exit("Thumbnail generator: Can't GenerateThumbnail.");
	}
}



<?php

/**
 * FPM doesn't read the env vars correctly, so this puts the values in
 * flat files so that fpm will have them
 */

$source = "/tmp/vars";
$destination = "/usr/local/etc/php-fpm.d/www.conf";
$handle = fopen($source, "r");

file_put_contents($destination, PHP_EOL.'clear_env=no'.PHP_EOL, FILE_APPEND);
while (($line = fgets($handle)) !== false) {
	$vars = explode(' ', $line);
	$parts = explode('=', $vars[1], 2);
	if (in($parts[0])) {
		$response = file_put_contents($destination, 'env['.$parts[0].'] = '.$parts[1], FILE_APPEND);
	}
}

function in($value)
{
	$whitelist = [
		'APP_ENV',
		'APP_DEBUG',
		'APP_KEY',
		'CACHE_DRIVER',
		'QUEUE_DRIVER',
		'SESSION_DRIVER',
		'PRIVATE_KEY',
		'TOKEN',
		'BUILD_PATH',
	];

	if (in_array($value, $whitelist)) {
		return true;
	}
}
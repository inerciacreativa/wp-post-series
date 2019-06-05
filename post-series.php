<?php
/**
 * Plugin Name: ic Post Series
 * Plugin URI:  https://github.com/inerciacreativa/wp-post-series
 * Version:     4.0.1
 * Text Domain: ic-post-series
 * Domain Path: /languages
 * Description: Gestor de series de artículos.
 * Author:      Jose Cuesta
 * Author URI:  https://inerciacreativa.com/
 * License:     MIT
 * License URI: https://opensource.org/licenses/MIT
 */

use ic\Framework\Framework;
use ic\Plugin\PostSeries\PostSeries;

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists(Framework::class)) {
	throw new RuntimeException(sprintf('Could not find %s class.', Framework::class));
}

if (!class_exists(PostSeries::class)) {
	$autoload = __DIR__ . '/vendor/autoload.php';

	if (file_exists($autoload)) {
		/** @noinspection PhpIncludeInspection */
		include_once $autoload;
	} else {
		throw new RuntimeException(sprintf('Could not load %s class.', PostSeries::class));
	}
}

include_once __DIR__ . '/source/helpers.php';

PostSeries::create(__FILE__);

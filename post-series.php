<?php
/**
 * Plugin Name: ic Post Series
 * Plugin URI:  https://github.com/inerciacreativa/wp-post-series
 * Version:     2.0.0
 * Text Domain: ic-post-series
 * Domain Path: /languages
 * Description: Gestor de series de artículos.
 * Author:      Jose Cuesta
 * Author URI:  https://inerciacreativa.com/
 * License:     MIT
 * License URI: https://opensource.org/licenses/MIT
 */

if (!defined('ABSPATH')) {
	exit;
}

include_once __DIR__ . '/vendor/autoload.php';
include_once __DIR__ . '/source/helpers.php';

ic\Plugin\PostSeries\PostSeries::create(__FILE__);

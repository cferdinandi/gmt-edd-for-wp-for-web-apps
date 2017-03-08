<?php

/**
 * Plugin Name: GMT EDD for WordPress for Web Apps
 * Plugin URI: https://github.com/cferdinandi/gmt-edd-for-wp-for-web-apps/
 * GitHub Plugin URI: https://github.com/cferdinandi/gmt-edd-for-wp-for-web-apps/
 * Description: Adds EDD integration to WordPress for Web Apps
 * Version: 1.1.0
 * Author: Chris Ferdinandi
 * Author URI: http://gomakethings.com
 * Text Domain: gmt_courses
 * License: GPLv3
 */


// Load plugin files
require_once( plugin_dir_path( __FILE__ ) . 'includes/email.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/verification.php' );
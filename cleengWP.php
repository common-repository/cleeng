<?php
/*
Plugin Name: Cleeng for WordPress
Plugin URI: http://cleeng.com/?utm_source=WP&utm_medium=Link&utm_campaign=setting_page
Description:<strong>Important announcement</strong>: The Cleeng Wordpress plugin is no longer supported. After <strong>May 14th 2014</strong> all transactions made via this plugin won't be processed. Please check <a href="http://cleeng.com/">Cleeng.com</a> and <a href="http://cleeng.com/open">Cleeng.com/open</a> for alternative solutions.
Version: 2.5.4
Author: Cleeng
Author URI: http://cleeng.com/?utm_source=WP&utm_medium=Link&utm_campaign=setting_page
License: New BSD License (http://cleeng.com/license/new-bsd.txt)
*/

/**
 * Plugin bootstrap
 */
if ( version_compare( get_bloginfo('version'), '2.8', '>=' ) ) {
    $plugin_url = plugins_url('', __FILE__);
} else {
    $plugin_url = WP_PLUGIN_URL . '/' . plugin_basename(dirname(__FILE__));
}

// setup URL for assets
if (!defined('CLEENG_PLUGIN_URL')) {
    define('CLEENG_PLUGIN_URL',
    $plugin_url.'/');
}
//echo CLEENG_PLUGIN_URL; die;
if (!class_exists('Cleeng_Core')) { // check if another instance of Cleeng For WordPress is already activated
    // load translations
    $pluginName = end(explode('/',$plugin_url));
    load_plugin_textdomain('cleeng', false, $pluginName . '/languages/');

    // load and setup Cleeng_Core class,
    require_once dirname(__FILE__) . '/php/classes/Core.php';
    Cleeng_Core::get_instance()->setup();

    // register activation hook - it must be dont inside this file
    register_activation_hook(__FILE__, array('Cleeng_Core', 'activate'));
    register_deactivation_hook(__FILE__, array('Cleeng_Core', 'deactivate'));
} else {
    if (!function_exists('cleeng_multiple_instance_warning')) {
        function cleeng_multiple_instance_warning()
        {
            echo '<div class="updated">';
            echo '<p>Warning: you have multiple instances of Cleeng For WordPress installed. All additional installations will be disabled.</p>';
            echo '</div>';
        }

        add_action('admin_notices', 'cleeng_multiple_instance_warning');
    }
}
add_option('default_values');

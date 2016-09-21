<?php
/*
Plugin Name: Retribal Music Festival Plugin
Plugin URI: https://retribal.com/free-music-festival-wordpress-plugin/
Description: The Free Retribal Music Festival plugin works with any theme and is full of features to engage your fans, sponsors, artists, staff, volunteers, venues and stages.
Version: 1.0
Author: AlpineIO
Author URI: https://retribal.com/
License: Apache License, Version 2.0
License URI: http://www.apache.org/licenses/LICENSE-2.0
*/

// First check version

if (version_compare(PHP_VERSION, '5.4', '<')) {

    add_action('admin_notices', create_function('', "
        echo '<div class=\"error\"><p>" . __('Plugin Name requires PHP 5.4 to function properly. ') 
        			. _( 'Please upgrade PHP. The Plugin has been auto-deactivated.', 'plugin-name') . "</p></div>'; 
        if (isset($_GET[activate])){
            unset($_GET[activate])
            };
        "));

    add_action('admin_init', 'pluginname_deactivate_self');

    function pluginname_deactivate_self()
    {
        deactivate_plugins(plugin_basename(__FILE__));
    }
    return;
    
} else {

	// Compatible, suit up!
	include_once('rtrbl_loader.php');

}

?>
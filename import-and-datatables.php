<?php
/**
 * Plugin Name: Excel Import and Datatables
 * Plugin URI:
 * Description: Pulls in a excel file and creates a list of the data.
 * Version: 1.0.0
 * Author: Matt Guthrie
 * Author URI: http://www.mattguthrie.co
 * License: GPL2
 */

define('EXCEL_IMPORT_AND_DATATABLES', '1.0.0');

if (!class_exists('name_here')) :
    class name_here {
        function init() {
            //includes
            // include_one('includes/');
        }
    }

    function name_here() {
        global $plugin_data;
        if(!isset($plugin_data)) {
            $plugin_data = new name_here();
            $plugin_data->init();
        }
    }

    function name_here_active() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $tablename = $wpdb->prefix . "table_name_here";
        $sql = "CREATE TABLE $tablename (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            manufacturer varchar(255) NOT NULL,
            part_number varchar(255) NOT NULL,
            part_description text character set utf8 NOT NULL,
            quantity_available varchar(255) NOT NULL,
            price_quantities varchar(255) NOT NULL,
            price_usd varchar(255) NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";
        dbDelta($sql);
    }

    register_activation_hook(__FILE__, 'name_here_activate');

    name_here();

endif;
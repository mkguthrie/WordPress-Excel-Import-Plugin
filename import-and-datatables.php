<?php
/**
 * Plugin Name: Excel Import and Datatables
 * Plugin URI:
 * Description: Import an Excel file and using a shortcode, output a table of data on the frontend.
 * Version: 1.0.0
 * Author: Matt Guthrie
 * Author URI: http://www.mattguthrie.co
 * License: GPL2
 */

define('EXCEL_IMPORT_AND_DATATABLES', '1.0.0');

if (!class_exists('datatable_list')) :
    class datatable_list {
        function init() {
            //includes
            include_once('includes/data_admin.php');
            include_once('includes/data_public.php');
        }
    }

    function datatable_list() {
        global $plugin_data;
        if(!isset($plugin_data)) {
            $plugin_data = new datatable_list();
            $plugin_data->init();
        }
    }

    function datatables_list_activate() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        $tablename = $wpdb->prefix . "datatables_data";
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

    register_activation_hook(__FILE__, 'datatables_list_activate');

    datatable_list();

endif;
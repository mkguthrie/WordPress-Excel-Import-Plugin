<?php

if ( ! class_exists( 'data_public' ) ) :

	class data_public {

		function __construct() {
			add_action( 'wp_enqueue_scripts', array( $this, 'datatables_enqueues') );
			add_shortcode( 'datatables_list_shortcode', array($this, 'datatables_list_function' ) );
			add_action( 'wp_ajax_get_table_data', array( $this, 'get_table_data' ) );
			add_action( 'wp_ajax_nopriv_get_table_data', array( $this, 'get_table_data' ) );
		}

		function datatables_enqueues () {
			wp_enqueue_style( 'datatables-css','//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css', array(), '1.0' );
			wp_enqueue_style( 'datatables-css-responsive','https://cdn.datatables.net/responsive/2.2.1/css/responsive.dataTables.min.css', array(), '1.0' );
			wp_register_script('datatables-js', '//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js', array('jquery'), '1.0');
			wp_register_script('datatables-js-responsive', 'https://cdn.datatables.net/responsive/2.2.1/js/dataTables.responsive.min.js', array('jquery'), '1.0');
			wp_enqueue_script('datatables-js');
			wp_enqueue_script('datatables-js-responsive');
            wp_enqueue_script( 'frontend-ajax', plugin_dir_url( __FILE__ ) . 'js/public.js', array('jquery'), null, true );
            wp_localize_script( 'frontend-ajax', 'frontend_ajax_object',
                array( 
                    'ajaxurl' => admin_url( 'admin-ajax.php' ),
                )
            );
		}

		function datatables_list_function() {
			ob_start();
			?>
			<table id="datatables-list" class="display" style="width:100%">
				<thead>
				<tr>
					<th>Manufacturer</th>
					<th>Part Number</th>
					<th>Description</th>
					<th>Quantity Availabile</th>
					<th>Price Quantities</th>
					<th>Price (USD)</th>
				</tr>
				</thead>
			</table>
			<?php
			$content =  ob_get_contents();
			ob_clean();
			return $content;
		}

		function get_table_data() {
			$page_number = 1;
		    if($_REQUEST['start'] != 0){
			    $page_number = ($_REQUEST['start'] / 5) + 1;
            }
		    $results = $this->run_query(5, $page_number);
		    $total_count = $this->posts_count();
			$response = array(
                "draw" => $_REQUEST['draw'] + 1,
                "recordsTotal" => $total_count,
				"recordsFiltered" => $total_count,
                "data" => $results
            );
			echo wp_json_encode($response);
			wp_die();
		}

		function run_query($per_page, $page_number = 1) {
			global $wpdb;
			$sql = "SELECT * FROM {$wpdb->prefix}datatables_data";
			$search = ( isset( $_REQUEST['search']['value'] ) ) ? $_REQUEST['search']['value'] : false;
			if($search ){
				$sql .= sprintf(" WHERE part_number LIKE '%%%s%%' OR manufacturer LIKE '%%%s%%'", $search, $search);
			}
			if ( ! empty( $_REQUEST['order'] ) ) {
			    $column = $_REQUEST['order'][0]['column'];
			    $order_by = $_REQUEST['columns'][$column]['data'];
				$sql .= ' ORDER BY ' . esc_sql( $order_by );
				$sql .= ! empty( $_REQUEST['order'][0]['dir'] ) ? ' ' . esc_sql( $_REQUEST['order'][0]['dir'] ) : ' ASC';
			}
			$sql .= " LIMIT $per_page";
			$sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;
			$results = $wpdb->get_results( $sql, 'ARRAY_A' );
			return $results;
		}


		function posts_count() {
			global $wpdb;
			$sql = "SELECT COUNT(*) FROM {$wpdb->prefix}datatables_data";
			$search = ( isset( $_REQUEST['search']['value']  ) ) ? $_REQUEST['search']['value']  : false;
			if($search ){
				$sql .= sprintf(" WHERE part_number LIKE '%%%s%%' OR manufacturer LIKE '%%%s%%'", $search, $search);
			}
			return $wpdb->get_var( $sql );
		}

	}

	new data_public;

endif;

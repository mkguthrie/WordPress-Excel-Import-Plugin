<?php



use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;

if ( ! class_exists( 'data_admin' ) ) :

	class data_admin {

		function __construct() {
			add_action( 'admin_menu', array( $this, 'register_admin_menu' ), 10 );
		}

		function register_admin_menu() {
            add_menu_page( 'DataTables List', 'DataTables List', 'edit_pages', 'datatables_list_import', array($this, 'get_import_page'), 'dashicons-editor-ol', 10 ); 
		}


		function get_import_page() {
            require_once( ABSPATH . 'wp-content/plugins/import-and-datatables/vendor/autoload.php' );

            if(isset($_POST['data_import_sumbit'])){
                global $wpdb;
                $tablename = $wpdb->prefix."datatables_data";
                $wpdb->query("TRUNCATE TABLE $tablename");
                if ( !empty( $_FILES['file']['name'] ) ) {
                    $filePath = $_FILES['file']['tmp_name'];
                    $reader = ReaderEntityFactory::createXLSXReader();
                    $reader->open($filePath);
                    $totalInserted = 0;
                    $time_start = microtime(true);
                    $counter = 0;
                    $q = "INSERT INTO $tablename (manufacturer, part_number, part_description, quantity_available, price_quantities, price_usd) VALUES ";
                    foreach ($reader->getSheetIterator() as $sheet) {
                        foreach ($sheet->getRowIterator() as $row) {
                            if ($counter++ == 0) continue;

                            // vars
                            $cells = $row->getCells();
                            $avail_qty = $cells[4]->getValue();
                            if ( $avail_qty < 1 ) continue;
                            $part_number = trim($cells[0]->getValue());
                            $manufacturer = trim($cells[1]->getValue());
                            $description = trim($cells[2]->getValue());
                            $price_tiers = $cells[6]->getValue();
                            $price_usd = $cells[7]->getValue();
                            
                            $q .= $wpdb->prepare(
                                "(%s, %s, %s, %d, %s, %s),",
                                $manufacturer, $part_number, $description, $avail_qty, $price_tiers, $price_usd
                            );

                            $totalInserted++;

                        }
                    }
                    $q = rtrim( $q, ',' ) . ';';
                    $wpdb->query( $q );
                    $time_end = microtime(true);
                    $time = $time_end - $time_start;
                    $reader->close();
                    echo "<h3 style='color: green;margin-bottom:0px;'>Total Records Inserted : " . $totalInserted . "</h3>";
                    echo "<p style='margin: 2px;font-size: 10px;'>Total Time : " . $time . "</p>";
                } else {
                    echo "<h3 style='color: red;'>Invalid Extension</h3>";
                }
                
            } 
            
			?>
            <div class="wrap">
                <h1 class="wp-heading-inline">Import Excel Spreadsheet</h1>
                <p style="margin: 0;">Previous uploads are removed from the database with each import.</p>
                <form action="#" method="post" name="myForm" enctype="multipart/form-data" class="upload_excel" style="padding: 15px 0;"> 
                    <input type="file" name="file" id="upload_file">
                    <input type= "submit" value="upload" name="data_import_sumbit" class="submit button">
                </form>
                <p style='display:inline-block;border-top:1px solid #000;padding-top:5px;margin-top:0px;font-size:12px;'>Shortcode to place table of data on page: <b>[datatables_list_shortcode]</b></p>
            </div>
			<?php
		}
	}

	new data_admin();

endif;
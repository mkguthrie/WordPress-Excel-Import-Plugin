(function( $ ) {
    $(document).ready(function() {
        $('#datatables-list').DataTable({
            iDisplayLength: 5,
            responsive: true,
            bLengthChange: false,
            processing: true,
            serverSide: true,
            search: true,
            ajax: {
                data: {
                    action: 'get_table_data'
                },
                dataType: "json",
                url: frontend_ajax_object.ajaxurl,
                type: 'POST'
            },
            columns: [
                { "data": "manufacturer" },
                { "data": "part_number" },
                { "data": "part_description" },
                { "data": "quantity_available" },
                { "data": "price_quantities" },
                { "data": "price_usd" },
            ]
        });
    } );
})( jQuery );
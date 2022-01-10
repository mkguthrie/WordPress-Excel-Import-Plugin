A WordPress plugin that imports an .xlsx file into a custom table of the WP database.

Shortcode to show a table of date on the front end: [datatables_list_shortcode]. The jQuery library DataTables is used to create and display table.

The import part of the plugin is built using Spout; a php library to read and write spreadsheets in a fast and scalable way.

Potential improvements:
- replace DataTables CDN links and add with package manager (NPM, bower, composer, etc.)
- dynamically handle column names (currently hardcoded)
- show imported data on admin side with WP_List_Table class
- allow admins to search, edit, delete, add a row

![Plugin Import](/img/plugin-import.png)

![Plugin Datatables](/img/plugin-example.png)
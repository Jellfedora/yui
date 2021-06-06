<?php
/**
 * Fedora plugins class
 *
 * @author Jellfedora <julienlecointe@live.fr>
 */
class FedoraAddPhototeque
{
    /**
     * When plugin activation
     *
     * @return nothing or error
     */
    public function activation()
    {
        $this->createTables();

        flush_rewrite_rules();
    }

    /**
     * When plugin deactivation
     *
     * @return nothing or error
     */
    public function deactivation()
    {
        $this->removeTables();
        flush_rewrite_rules();
    }

    

    /**
     * Create tables phototeque_settings and phototeque_photos
     *
     * @return nothing or error
     */
    public function createTables()
    {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        // Table settings
        $table_settings_name = $wpdb->prefix . 'phototeque_settings';

        $sql = "CREATE TABLE $table_settings_name (
            -- id INT PRIMARY KEY NOT NULL ,
            folder_path VARCHAR(255)


        ) $charset_collate;";

        include_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);

        // Insert default settings folder path
        $wpdb->insert(
            $table_settings_name,
            array(
            'folder_path' => '../votre_dossier'
            )
        );

        // Table photos
        $table_photo_name = $wpdb->prefix . 'phototeque_photos';

        $sql3 = "CREATE TABLE $table_photo_name (
            id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
            name VARCHAR(255) NOT NULL,
            created_at DATETIME NOT NULL,
            extension VARCHAR(10) NOT NULL,
            path VARCHAR(255) NOT NULL,
            size VARCHAR(50) NOT NULL
        ) $charset_collate;";

        dbDelta($sql3);
    }

    /**
     * Drop tables phototeque_settings and phototeque_photos
     *
     * @return nothing or error
     */
    public function removeTables()
    {
        global $wpdb;
        $tbl_array = [
            $wpdb->prefix . "phototeque_photos",
            $wpdb->prefix . "phototeque_settings"
        ];

        foreach ($tbl_array as $tbl_name) {
            $wpdb->query("DROP TABLE IF EXISTS $tbl_name");
        }
    }
}
<?php
/**
 * Add the photo library menu in the Settings submenu
 *
 * @author Jellfedora <julienlecointe@live.fr>
 */
class FedoraAddPhototequeSettings
{
    /**
     * Construct
     *
     * @return nothing
     */
    public function __construct()
    {
        add_action('admin_menu', [$this, 'addPhototequeSettings']);
        add_action('admin_enqueue_scripts', [$this, 'loadAssets']);

        // Ajax
        add_action('wp_ajax_search_in_folder', [$this, 'importPhotos']);
        add_action('wp_ajax_nopriv_search_in_folder', [$this, 'importPhotos']);
        add_action('wp_ajax_save_folder_path', [$this, 'saveFolderPath']);
        add_action('wp_ajax_nopriv_save_folder_path', [$this, 'saveFolderPath']);
    }

    /**
     * Load Assets for Js and Css files
     *
     * @return nothing
     */
    public function loadAssets()
    {
        $plugin_url = plugin_dir_url(__FILE__);

        // Js
        wp_enqueue_script('js-file', $plugin_url . '../assets/settings/js/index.js');
        // Css
        wp_enqueue_style('css-file', $plugin_url . '../assets/settings/css/style.css');
        wp_enqueue_style('css-file', $plugin_url . '../assets/settings/css/coucou.css');
        // Ajax
        wp_add_inline_script('js-ajax', 'ajaxurl', admin_url('admin-ajax.php'));
    }

    /**
     * Create Phototeque settings menu
     *
     * @return nothing
     */
    public function addPhototequeSettings()
    {
        register_setting('reading', 'page_limit');

        // register a new section in the "reading" page
        add_options_page(
            'Phototeque', //id
            'Phototèque', //title
            'manage_options', //capacity
            'phototeque', //slug
            array(
            $this,
            'phototequeSettingsPage'
            ), // function
            1// Position dans le menu
        );
    }

    /**
     * Add html to phototeque settings menu
     *
     * @return html
     */
    public function phototequeSettingsPage()
    {
        global $wpdb;

        $table_name = $wpdb->prefix . "phototeque_settings";

        $result = $wpdb->get_results("SELECT * FROM $table_name");

        if ($result) {
            $folder_path = $result[0]->folder_path;
        } else {
            $folder_path = null;
        } ?>
<div class="phototeque">
    <h2 class="phototeque__title">Phototèque</h2>
    <p>Ce plugin permet d'importer toutes les images d'un dossier <strong>(et de ses sous dossiers!)</strong> dans le
        menu
        Phototèque
        ajouté avec ce plugin.</p>
    <div class="phototeque__settings">
        <h3 class="phototeque__settings__title">Configuration</h3>
        <p><span class="phototeque__settings__warning">Attention</span>, si votre dossier est plus haut dans la
            hiérarchie que ce plugin il faudra remonter
            d'un parent à
            partir de ce dernier, en
            utilisant ../ jusqu'à arriver au dossier choisit.</p>
        <p>Exemple: "/../../../../../../dossier-images"</p>
        <p>Cet emplacement sera sauvegardé en base de donnée.</p>
        <form class="phototeque__settings__form">
            <label for="folder-path-input" class="phototeque__settings__form__label">Emplacement de votre dossier
                contenant les images :</label>
            <div class="phototeque__settings__form__container">
                <input type="text" id="folder-path-input" name="folder-path-input"
                    class="phototeque__settings__form__container__input" value="<?php echo $folder_path ?>">
                <button type="submit" id="folder_path_submit"
                    class="phototeque__settings__form__container__submit">Sauvegarder</button>
            </div>
        </form>
    </div>

    <div class="phototeque__import">
        <h3 class="phototeque__import__title">Rechercher et sauvegarder les images dans la phototèque</h3>
        <p class="phototeque__import__warning">Attention, si les images sont déjà présentes en Base de données, elles
            seront dupliquées!</p>
        <button class="phototeque__import__submit">Valider</button>
        <p class="phototeque__import__message" style="visibility:hidden;"> Le chemin spécifié est invalide ou interdit
        </p>
    </div>
    <div class="phototeque__photos-container photos-container"></div>
</div>


<?php
    }

    /**
     * Sauvegarde le chemin du dossier d'images
     *
     * @return nothing
     */
    public function saveFolderPath()
    {
        if (isset($_POST['folder_path'])) {
            $path = $_POST['folder_path'];
            // Save path in bdd
            global $wpdb;

            $table_name = $wpdb->prefix . "phototeque_settings";

            $query = $wpdb->query(
                $wpdb->prepare(
                    "
                UPDATE $table_name SET folder_path = '$path' "
                )
            );

            // TODO Retourne success or error
            header("Content-Type: application/json");
            echo json_encode($query);
        } else {
            echo json_encode("error");
        }

        wp_die();
    }

    /**
     * Sauvegarde les photos en Bdd
     *
     * @return json
     */
    public function importPhotos()
    {
        $path = __DIR__ . $_POST['folder_path'];

        // TODO à effacer
        // Chemin du dossier
        // $path = '/home/jellfedora/Images';
        // $path = __DIR__ . "/../../../../../../images_pour_test" ;

        // Récupére le nom du dossier des images
        $explode_folder_name = explode("/", $path);
        $folder_name = $explode_folder_name[count($explode_folder_name) - 1];

        // Rechercher image dans le dossier
        $results = [];
        $errors=false;

        try {
            $images = $this->getPhotos($path, $folder_name);
        } catch (Exception $e) {
            $errors = true;
            $results['data'] = array(
                'status' => '422',
                // 'message' => $e->getMessage(),
                'message' => "Impossible de trouver ou d'ouvrir le dossier " . $path
            );
        }

        if (!$errors) {
            $results['data'] = array(
                'status' => '201',
                'images' => $images,
                'message' => count($images) . " images trouvée(s)"
            );
        }
        // Retourne les photos trouvées dans le dossier
        header("Content-Type: application/json");
        echo json_encode($results);

        wp_die();
    }

    /**
     * Sauvegarde les photos en Bdd
     *
     * @return nothing or error
     */
    public function savePhotos($files)
    {
        global $wpdb;
        $table_photo_name = $wpdb->prefix . 'phototeque_photos';

        foreach ($files as $file) {
            $wpdb->insert($table_photo_name, $file);
        }
    }

    /**
     * Explore le dossier à la recherche d'images
     *
     * @return files_infos
     */
    public function getPhotos($path, $folder_name)
    {
        $di = new RecursiveDirectoryIterator($path);
        $iterator = new RecursiveIteratorIterator($di, RecursiveIteratorIterator::CHILD_FIRST);

        $files = [];
        foreach ($iterator as $file) {
            // Ne garde que si extension = png / jpg / jpeg /
            if ($file->getExtension() === "png" || $file->getExtension() === "jpg" || $file->getExtension() === "jpeg") {

                // Si le fichier est dans un sous dossier
                $file_folder = $file->getPath();
                $explode_folder_name = explode("/", $file_folder);

                // Retenir la position de image pour test dans larray
                $folder_name_position = array_search($folder_name, $explode_folder_name); //=16 sur 21 items

                // Récupére tout les dossiers
                $folders = array_slice($explode_folder_name, $folder_name_position, count($explode_folder_name));

                // Assemble tout les noms de dossiers parents
                $concatenate_folders = implode('/', $folders);

                $file_folder_name = $explode_folder_name[count($explode_folder_name) - 1];

                if ($file_folder_name !== $folder_name) {
                    $files[] = [
                        "path" => $_SERVER["HTTPS"] . "/" . $concatenate_folders . "/" . $file->getFilename(),
                        "extension" => $file->getExtension(),
                        "name" => $file->getFilename(),
                        // "created_at" => date("d F Y H:i:s.", $file->getATime()),
                        "created_at" => date('Y-m-d', $file->getATime()),
                        "size" => $this->formatSizeUnits(filesize($file))
                    ];
                } else {
                    $files[] = [
                        "path" => $_SERVER["HTTPS"] . "/images_pour_test/" . $file->getFilename(),
                        "extension" => $file->getExtension(),
                        "name" => $file->getFilename(),
                        // "created_at" => date("d F Y H:i:s.", $file->getATime()),
                        "created_at" => date('Y-m-d', $file->getATime()),
                        "size" => $this->formatSizeUnits(filesize($file))
                    ];
                }
            }
        }

        $this->savePhotos($files);
        asort($files);
        return $files;
    }

    /**
     * Formate les bytes en GB/MB/KB
     *
     * @return bytes
     */
    public function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }
}
<?php
/**
 * Fedora plugins class
 *
 * @author Jellfedora <julienlecointe@live.fr>
 */
class FedoraAddPhototequeCpt
{
    /**
     * Construct
     *
     * @return nothing
     */
    public function __construct()
    {
        add_action('init', [$this, 'addPhototeque']);
    }

    /**
     * Create CPT Phototéque
     *
     * @return nothing or error
     */
    public function addPhototeque()
    {
        $labels = array(
        'name' => 'Phototéque',
        'all_items' => 'Toutes les photos', // affiché dans le sous menu
        'singular_name' => 'Photo',
        'add_new_item' => 'Ajouter une photo',
        'edit_item' => 'Modifier les photos',
        'menu_name' => 'Phototéque'
        );

        $args = array(
                'labels' => $labels,
        'public' => true,
        'show_in_rest' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail'),
        'menu_position' => 5,
        'menu_icon' => 'dashicons-format-gallery'
        );

        register_post_type('phototeque', $args);
    }
}
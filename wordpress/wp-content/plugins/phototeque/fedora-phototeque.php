<?php
/**
 * @category Media
 * @package  Fedora_Phototeque
 * @author   Julien Lecointe <julienlecointe@live.fr>
 * @license  GPL-2.0 <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @version  "CVS: <cvs_id>"
 * @link     https://github.com/Jellfedora?tab=repositories
 * @PHP
 * Plugin Name: Fedora Phototeque
 * Plugin URI: http://wordpress.org/plugins/fedora-phototeque/
 * Description: Galerie d'images avec import automatique
 * Version: 1
 * Author URI: https://devodyssey.fr/
 */

if (!defined('ABSPATH')) {
    exit;
}

// Instantiation
require plugin_dir_path(__FILE__). 'inc/index.php';
$fedora_add_phototeque = new FedoraAddPhototeque();

// Plugin Settings
require plugin_dir_path(__FILE__). 'inc/phototeque_settings.php';
$fedora_add_phototeque = new FedoraAddPhototequeSettings();

// Custom Post Type
require plugin_dir_path(__FILE__). 'inc/phototeque_cpt.php';
$fedora_add_phototeque = new FedoraAddPhototequeCpt();

register_activation_hook(__FILE__, [$fedora_add_phototeque, 'activation']);
register_deactivation_hook(__FILE__, [$fedora_add_phototeque, 'deactivation']);
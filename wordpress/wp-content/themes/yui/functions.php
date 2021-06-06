<?php 

// Supprimer certaines options du menu (Hooks)

//     add_action( 'admin_menu', 'yui_remove_menus' );
// function yui_remove_menus(){
   
//     remove_menu_page( 'index.php' );                  //Dashboard
//     remove_menu_page( 'jetpack' );                    //Jetpack* 
//     remove_menu_page( 'edit.php' );                   //Posts
//     remove_menu_page( 'upload.php' );                 //Media
//     remove_menu_page( 'edit.php?post_type=page' );    //Pages
//     remove_menu_page( 'edit-comments.php' );          //Comments
//     remove_menu_page( 'themes.php' );                 //Appearance
//     remove_menu_page( 'plugins.php' );                //Plugins
//     remove_menu_page( 'users.php' );                  //Users
//     remove_menu_page( 'tools.php' );                  //Tools
//     remove_menu_page( 'options-general.php' );        //Settings
     
//   }

// Scinder functions.php

// Configuration du thème
// require_once get_template_directory() . '/inc/config.php';

// Types de publication et taxonomies
// require_once get_template_directory() . '/inc/post-types.php';

// Fonctionnalités
// require_once get_template_directory() . '/inc/features.php';
  
// Importer des assets

function yui_register_assets() {
    
    // Déclarer jQuery
    // wp_enqueue_script('jquery' );
    
    // Déclarer le JS
	// wp_enqueue_script( 
    //     'yui', 
    //     get_template_directory_uri() . '/js/script.js', 
    //     array( 'jquery' ), 
    //     '1.0', 
    //     true
    // );
    
    // Déclarer style.css à la racine du thème
    // wp_enqueue_style( 
    //     'yui_style',
    //     get_stylesheet_uri(), 
    //     array(), 
    //     '1.0'
    // );

    // Chargement de la feuille de style complémentaire du thème enfant
    
 	// wp_enqueue_style( 'yui-theme', get_template_directory_uri() . '/styles/compile-css/index.css' );
  	
    // Déclarer un autre fichier CSS
    // wp_enqueue_style( 
    //     'yui', 
    //     get_template_directory_uri() . '/css/main.css',
    //     array(), 
    //     '1.0'
    // );

    // Déclarer un script Seulement sur la page d'accueil
    // if( is_front_page() ) {
    // 	wp_enqueue_script( 'diaporama', ... );
    // }
}
add_action( 'wp_enqueue_scripts', 'yui_register_assets' );
  
  // Ajouter la prise en charge des images mises en avant
  add_theme_support( 'post-thumbnails' );
  
  // Ajouter automatiquement le titre du site dans l'en-tête du site
  add_theme_support( 'title-tag' );






// function yui_register_phototeque_taxonomy() {
// 	$labels = array( );
//     $args = array();
    
//     register_post_type( 'phototeque', $args );
    
//     // Déclaration de la Taxonomie
//     $labels = array(
//         'name' => 'Type de projets',
//         'new_item_name' => 'Nom du nouveau Projet',
//     	'parent_item' => 'Type de projet parent',
//     );
    
//     $args = array( 
//         'labels' => $labels,
//         'public' => true, 
//         'show_in_rest' => true,
//         'hierarchical' => true, 
//     );

//     register_taxonomy( 'type-projet', 'phototeque', $args );
// }
// add_action( 'init', 'yui_register_phototeque_taxonomy' );
  ?>